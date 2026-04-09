<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;

class BackupDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup
                            {--disk=local : Disco donde guardar (local, s3, ftp, sftp)}
                            {--path=backups : Ruta dentro del disco}
                            {--compress : Comprimir el backup con gzip}
                            {--notify : Enviar notificación por email al terminar}
                            {--remote-host= : Host SSH remoto (ej: user@192.168.1.10)}
                            {--remote-path= : Ruta en el servidor remoto}
                            {--remote-port=22 : Puerto SSH}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera un backup de la base de datos y lo guarda en el disco configurado o servidor remoto';

    protected EmailService $emailService;

    public function __construct(EmailService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔄 Iniciando backup de base de datos...');

        $connection = config('database.default');
        $driver     = config("database.connections.{$connection}.driver");

        // Validar driver soportado
        if (!in_array($driver, ['mysql', 'pgsql', 'sqlite'])) {
            $this->error("Driver [{$driver}] no soportado para backup automático.");
            return self::FAILURE;
        }

        $filename  = $this->generateFilename($connection);
        $tempPath  = storage_path("app/temp/{$filename}");

        // Crear directorio temporal
        if (!is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }

        // Generar el dump según el driver
        $dumpResult = match($driver) {
            'mysql'  => $this->dumpMysql($tempPath),
            'pgsql'  => $this->dumpPostgres($tempPath),
            'sqlite' => $this->dumpSqlite($tempPath),
        };

        if (!$dumpResult) {
            return self::FAILURE;
        }

        // Comprimir si se solicita
        if ($this->option('compress')) {
            $tempPath = $this->compressFile($tempPath);
            $filename = basename($tempPath);
        }

        // Guardar en el destino correcto
        $saved = $this->option('remote-host')
            ? $this->saveToRemote($tempPath, $filename)
            : $this->saveToDisk($tempPath, $filename);

        $filesize = $this->formatBytes(filesize($tempPath) ?: 0);

        // Limpiar archivo temporal
        @unlink($tempPath);

        if (!$saved) {
            return self::FAILURE;
        }
        
        $this->info("✅ Backup completado: {$filename}");
        $this->line("   Disco: <comment>{$this->option('disk')}</comment>");
        $this->line("   Ruta:  <comment>{$this->option('path')}/{$filename}</comment>");
        $this->line("   Tamaño: <comment>{$filesize}</comment>");

        if ($this->option('notify')) {
            $this->sendNotification($filename, $filesize, $this->option('path'), $this->option('disk'));
        }

        return self::SUCCESS;
    }

    // -------------------------------------------------------
    // Generación de dumps
    // -------------------------------------------------------

    protected function dumpMysql(string $outputPath): bool
    {
        $config = config('database.connections.' . config('database.default'));

        $command = [
            'mysqldump',
            '--user='    . $config['username'],
            '--password='. $config['password'],
            '--host='    . $config['host'],
            '--port='    . $config['port'],
            '--single-transaction',
            '--quick',
            '--lock-tables=false',
            $config['database'],
        ];

        return $this->runDump($command, $outputPath);
    }

    protected function dumpPostgres(string $outputPath): bool
    {
        $config = config('database.connections.' . config('database.default'));

        $env = ['PGPASSWORD' => $config['password']];

        $command = [
            'pg_dump',
            '--username=' . $config['username'],
            '--host='     . $config['host'],
            '--port='     . $config['port'],
            '--no-password',
            $config['database'],
        ];

        return $this->runDump($command, $outputPath, $env);
    }

    protected function dumpSqlite(string $outputPath): bool
    {
        $source = config('database.connections.sqlite.database');

        if (!file_exists($source)) {
            $this->error("Base de datos SQLite no encontrada: {$source}");
            return false;
        }

        if (!copy($source, $outputPath)) {
            $this->error("No se pudo copiar el archivo SQLite.");
            return false;
        }

        return true;
    }

    protected function runDump(array $command, string $outputPath, array $env = []): bool
    {
        try {
            $process = new Process($command, null, $env);
            $process->setTimeout(300); // 5 minutos máximo
            $process->run();

            if (!$process->isSuccessful()) {
                $this->error('Error al generar dump: ' . $process->getErrorOutput());
                return false;
            }

            file_put_contents($outputPath, $process->getOutput());
            return true;

        } catch (\Exception $e) {
            $this->error('Excepción al ejecutar dump: ' . $e->getMessage());
            return false;
        }
    }

    // -------------------------------------------------------
    // Compresión
    // -------------------------------------------------------

    protected function compressFile(string $filePath): string
    {
        $this->line('🗜️  Comprimiendo archivo...');

        $gzPath = $filePath . '.gz';

        $input  = fopen($filePath, 'rb');
        $output = gzopen($gzPath, 'wb9'); // Nivel de compresión 9

        while (!feof($input)) {
            gzwrite($output, fread($input, 1024 * 512));
        }

        fclose($input);
        gzclose($output);

        @unlink($filePath); // Eliminar el original sin comprimir

        $this->line('   Compresión completada.');

        return $gzPath;
    }

    // -------------------------------------------------------
    // Almacenamiento en disco Laravel (local, s3, ftp, sftp)
    // -------------------------------------------------------

    protected function saveToDisk(string $tempPath, string $filename): bool
    {
        $disk       = $this->option('disk');
        $remotePath = $this->option('path') . '/' . $filename;

        $this->line("💾 Guardando en disco [{$disk}]...");

        try {
            $stream = fopen($tempPath, 'r');
            Storage::disk($disk)->writeStream($remotePath, $stream);

            if (is_resource($stream)) {
                fclose($stream);
            }

            return true;

        } catch (\Exception $e) {
            $this->error("Error al guardar en disco [{$disk}]: " . $e->getMessage());
            return false;
        }
    }

    // -------------------------------------------------------
    // Almacenamiento en servidor remoto vía SCP/SSH
    // -------------------------------------------------------

    protected function saveToRemote(string $tempPath, string $filename): bool
    {
        $remoteHost = $this->option('remote-host');
        $remotePath = $this->option('remote-path') ?? '/backups';
        $remotePort = $this->option('remote-port');

        $this->line("🌐 Enviando al servidor remoto [{$remoteHost}]...");

        $destination = "{$remoteHost}:{$remotePath}/{$filename}";

        $command = [
            'scp',
            '-P', $remotePort,
            '-o', 'StrictHostKeyChecking=no',
            '-o', 'BatchMode=yes',         // Sin contraseña interactiva (usa SSH keys)
            $tempPath,
            $destination,
        ];

        try {
            $process = new Process($command);
            $process->setTimeout(120);
            $process->run();

            if (!$process->isSuccessful()) {
                $this->error('Error SCP: ' . $process->getErrorOutput());
                return false;
            }

            $this->info("   Archivo enviado a {$destination}");
            return true;

        } catch (\Exception $e) {
            $this->error('Error al enviar por SCP: ' . $e->getMessage());
            return false;
        }
    }

    // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    protected function generateFilename(string $connection): string
    {
        $db        = config("database.connections.{$connection}.database");
        $dbName    = basename($db); // para SQLite trae la ruta completa
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');

        return "backup_{$dbName}_{$timestamp}.sql";
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) return round($bytes / 1073741824, 2) . ' GB';
        if ($bytes >= 1048576)    return round($bytes / 1048576, 2)    . ' MB';
        if ($bytes >= 1024)       return round($bytes / 1024, 2)       . ' KB';
        return $bytes . ' B';
    }

    protected function sendNotification(string $filename, string $filesize, string $path, string $disk): void
    {
        try {
            $to = config('mail.from.address');
            $subject = 'Backup de Base de Datos';
            $template = 'emails.blade.commands.db-backup';
            $data = [
                'fileName' => $filename,
                'size' => $filesize,
                'path' => $path,
                'disk' => $disk,
                'date' => Carbon::now()->format('Y-m-d H:i:s'),
            ];

            $this->emailService->sendBladeMail($to, $subject, $template, $data);

            $this->info('📧 Notificación enviada correctamente.');

        } catch (\Exception $e) {
            $this->error('Error al enviar notificación: ' . $e->getMessage());
        }
    }
}
