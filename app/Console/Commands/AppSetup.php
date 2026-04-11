<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AppSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configuración inicial del proyecto: .env, caches, migraciones y seeders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->comment('--- Iniciando configuración del proyecto ---');

        // 1. Asegurar existencia y configuración de .env
        $this->ensureEnvExists();
        $this->info('✅ Archivo .env verificado/configurado.');

        // 2. Generar APP_KEY si está vacío
        if (empty(env('APP_KEY'))) {
            $this->call('key:generate');
        }

        // 3. Generar claves de cifrado para Passport
        // --force evita prompts interactivos si las claves ya existen
        $this->call('passport:keys', ['--force' => true]);
        $this->info('🔑 Claves de Passport generadas/actualizadas.');

        // 4. Limpieza de cachés
        $this->call('optimize:clear');

        // 5. Base de datos y seeders
        $this->call('migrate:fresh', ['--force' => true]);
        $this->call('db:seed', ['--force' => true]);
        
        // 6. Enlace de storage
        $this->call('storage:link');

        $this->comment('--- Configuración finalizada con éxito ---');
        return Command::SUCCESS;
    }

    /**
     * Verifica si existe .env y lo crea si es necesario.
     */
    protected function ensureEnvExists(): void
    {
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            return;
        }

        $examplePath = base_path('.env.example');
        if (File::exists($examplePath)) {
            File::copy($examplePath, $envPath);
            $this->info('📄 .env creado desde .env.example');
        } else {
            // Fallback mínimo si no existe .env.example en la plantilla
            $baseEnv = "APP_NAME=Laravel\nAPP_ENV=local\nAPP_KEY=\nAPP_DEBUG=true\nAPP_URL=http://localhost\n";
            File::put($envPath, $baseEnv);
            $this->warn('⚠️ .env.example no encontrado. Se generó un .env básico.');
        }
    }
}
