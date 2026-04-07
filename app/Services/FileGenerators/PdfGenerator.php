<?php

namespace App\Services\FileGenerators;

use Illuminate\Support\Str;
use App\Contracts\FileGenerator;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;

class PdfGenerator implements FileGenerator
{

    /**
     * Genera un PDF con Barryvdh\DomPDF\Facade\Pdf, lo guarda en el disco y retorna la ruta del archivo.
     *
     * @param array $data Los datos para pasar a la vista.
     * @param array $options Opciones de generación (ej. 'view', 'filename', 'disk').
     * @param array $options['template'] |view route
     * @param array $options['prefix'] |string
     * @param array $options['filename'] |string
     * @param array $options['path'] |string
     * @param array $options['paper_size'] |string
     * @param array $options['orientation'] |string
     * @param array $options['encryption'] |array
     * @param array $options['encryption']['user'] |string
     * @param array $options['encryption']['owner'] |string
     * @param array $options['encryption']['permissions'] |array
     * @param array $options['encryption']['permissions'][] |string
     * @return string ruta del archivo guardado
     */
    public function generate(array $data, array $options): string
    {
        // Determina la vista
        $template = $options['template'] ?? 'pdf.default';

        // Determina el nombre del archivo
        $filename = isset($options['filename'])
            ? $options['filename'] . '.pdf'
            : $this->generateUniqueFilename($options['prefix'] ?? 'document-');

        // Determina la ruta del archivo
        $path = $options['path'] ?? storage_path('app/public/pdf');

        // Crear directorio temporal en caso de que no exista
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        // Determina el tamaño de la hoja
        $paperSize ='a4';

        if (!empty($options['paper_size'])) {
            if ($options['paper_size'] == '4a0'
            || $options['paper_size'] == '2a0'
            || $options['paper_size'] == 'a0'
            || $options['paper_size'] == 'a1'
            || $options['paper_size'] == 'a2'
            || $options['paper_size'] == 'a3'
            || $options['paper_size'] == 'a4'
            || $options['paper_size'] == 'a5'
            || $options['paper_size'] == 'a6'
            || $options['paper_size'] == 'a7'
            || $options['paper_size'] == 'a8'
            || $options['paper_size'] == 'a9'
            || $options['paper_size'] == 'a10'
            || $options['paper_size'] == 'b0'
            || $options['paper_size'] == 'b1'
            || $options['paper_size'] == 'b2'
            || $options['paper_size'] == 'b3'
            || $options['paper_size'] == 'b4'
            || $options['paper_size'] == 'b5'
            || $options['paper_size'] == 'b6'
            || $options['paper_size'] == 'b7'
            || $options['paper_size'] == 'b8'
            || $options['paper_size'] == 'b9'
            || $options['paper_size'] == 'b10'
            || $options['paper_size'] == 'c0'
            || $options['paper_size'] == 'c1'
            || $options['paper_size'] == 'c2'
            || $options['paper_size'] == 'c3'
            || $options['paper_size'] == 'c4'
            || $options['paper_size'] == 'c5'
            || $options['paper_size'] == 'c6'
            || $options['paper_size'] == 'c7'
            || $options['paper_size'] == 'c8'
            || $options['paper_size'] == 'c9'
            || $options['paper_size'] == 'c10'
            || $options['paper_size'] == 'ra0'
            || $options['paper_size'] == 'ra1'
            || $options['paper_size'] == 'ra2'
            || $options['paper_size'] == 'ra3'
            || $options['paper_size'] == 'ra4'
            || $options['paper_size'] == 'sra0'
            || $options['paper_size'] == 'sra1'
            || $options['paper_size'] == 'sra2'
            || $options['paper_size'] == 'sra3'
            || $options['paper_size'] == 'sra4'
            || $options['paper_size'] == 'letter'
            || $options['paper_size'] == 'half-letter'
            || $options['paper_size'] == 'legal'
            || $options['paper_size'] == 'ledger'
            || $options['paper_size'] == 'tabloid'
            || $options['paper_size'] == 'executive'
            || $options['paper_size'] == 'folio'
            || $options['paper_size'] == 'commercial #10 envelope'
            || $options['paper_size'] == 'catalog #10 1/2 envelope'
            || $options['paper_size'] == '8.5x11'
            || $options['paper_size'] == '8.5x14'
            || $options['paper_size'] == '11x17'
            ) {
                $paperSize = $options['paper_size'];
            }
        }

        // Determina la orientación de la hoja
        $orientation = 'portrait';

        if (!empty($options['orientation'])) {
            if ($options['orientation'] == 'landscape' || $options['orientation'] == 'portrait') {
                $orientation = $options['orientation'];
            }
        }

        // Establece una contraseña para el PDF
        $encryption = $options['encryption'] ?? [
            'user' => '',
            'owner' => '',
            'permissions' => ['print', 'modify', 'copy', 'add']
        ];

        // Carga la vista con los datos
        $pdf = PDF::loadView($template, $data);

        // Establece una contraseña para el PDF
        $pdf->setEncryption($encryption['user'], $encryption['owner'], $encryption['permissions']);

        // Guarda el PDF en el disco
        $fullPath = $path . '/' . $filename;

        $pdf->setPaper($paperSize, $orientation)->save($fullPath);

        return $fullPath;
    }

    /**
     * Genera un nombre de archivo único con la extensión .pdf.
     *
     * @return string ruta del archivo
     */
    protected function generateUniqueFilename($prefix = 'document'): string
    {
        return $prefix . Str::uuid()->toString() . '.pdf';
    }
}
