<?php

namespace Wali\FilamentWordExport\Services;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Wali\FilamentWordExport\Support\WordTemplateBuilder;

class WordExportService
{
    protected array $config;
    protected array $templateOverrides = [];

    public function __construct()
    {
        $this->config = config('filament-word-export', []);
    }

    /**
     * Export records to Word document.
     */
    public function export(array $records, array $options = []): BinaryFileResponse
    {
        $customTemplatePath = $options['custom_template_path'] ?? null;

        // If a custom template is provided, attempt to process it via TemplateProcessor
        if ($customTemplatePath) {
            return $this->exportWithCustomTemplate($records, $options, $customTemplatePath);
        }

        $phpWord = new PhpWord;
        $section = $phpWord->addSection();

        // Apply headers and footers using the template builder
        $templateBuilder = WordTemplateBuilder::make($this->config, $this->templateOverrides);
        $templateBuilder->applyTemplate($phpWord);

        // Create the data table
        $this->createDataTable($section, $records);

        // Generate and return the file
        return $this->generateFileFromPhpWord($phpWord, $options);
    }

    /**
     * Export using a user supplied .docx template. The template may optionally include a placeholder
     * ${TABLE_DATA} which will be replaced by a simple textual table representation since PHPWord's
     * TemplateProcessor does not directly support injecting table objects.
     */
    protected function exportWithCustomTemplate(array $records, array $options, string $relativePath): BinaryFileResponse
    {
        $storageDisk = $this->config['storage_disk'] ?? 'local';
        $disk = Storage::disk($storageDisk);

        if (! $disk->exists($relativePath)) {
            // Fallback silently to default export if template missing
            return $this->export($records, array_diff_key($options, ['custom_template_path' => true]));
        }

        $fullPath = $disk->path($relativePath);
        try {
            $templateProcessor = new TemplateProcessor($fullPath);
        } catch (\Throwable $e) {
            // On failure to load template, fallback
            return $this->export($records, array_diff_key($options, ['custom_template_path' => true]));
        }

        // Basic placeholder support: TABLE_DATA
        $templateProcessor->setValue('TABLE_DATA', $this->buildPlainTextTable($records));

        // Apply user-provided variables (key => value). Keys are used as-is.
        if (! empty($options['custom_template_variables']) && is_array($options['custom_template_variables'])) {
            foreach ($options['custom_template_variables'] as $key => $value) {
                // Ensure scalar string-ish
                $templateProcessor->setValue($key, is_scalar($value) ? (string) $value : json_encode($value));
            }
        }

        // Future: allow mapping of record fields to placeholders via user-provided map.

        $filename = $options['filename'] ?? 'filament-export-'.now()->format('Ymd_His').'.docx';
        $path = 'exports/'.$filename;

        $disk->makeDirectory('exports');
        $tempPath = $disk->path($path);
        $templateProcessor->saveAs($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    /**
     * Set template configuration overrides for this export.
     */
    public function withTemplateOverrides(array $overrides): static
    {
        $this->templateOverrides = array_merge($this->templateOverrides, $overrides);

        return $this;
    }

    /**
     * Disable headers for this export.
     */
    public function withoutHeader(): static
    {
        return $this->withTemplateOverrides(['header.enabled' => false]);
    }

    /**
     * Disable footers for this export.
     */
    public function withoutFooter(): static
    {
        return $this->withTemplateOverrides(['footer.enabled' => false]);
    }

    /**
     * Set custom header text for this export.
     */
    public function withHeaderText(string $text): static
    {
        return $this->withTemplateOverrides(['header.text' => $text]);
    }

    /**
     * Set custom footer text for this export.
     */
    public function withFooterText(string $text): static
    {
        return $this->withTemplateOverrides(['footer.text' => $text]);
    }

    /**
     * Create the data table in the document.
     */
    protected function createDataTable($section, array $records): void
    {
        if ($records === []) {
            $section->addText('No data to export.');

            return;
        }

        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '999999',
            'cellMargin' => 80,
        ]);

        foreach ($records as $record) {
            $table->addRow();
            foreach ($record as $value) {
                $table->addCell(2000)->addText($value ?? '-');
            }
        }
    }

    /**
     * Generate the Word file and return download response.
     */
    protected function generateFileFromPhpWord(PhpWord $phpWord, array $options): BinaryFileResponse
    {
        $filename = $options['filename'] ?? 'filament-export-'.now()->format('Ymd_His').'.docx';
        $storageDisk = $this->config['storage_disk'] ?? 'local';
        $path = 'exports/'.$filename;

        // Ensure the exports directory exists
        Storage::disk($storageDisk)->makeDirectory('exports');

        // Create empty file first
        Storage::disk($storageDisk)->put($path, '');

        // Get the full path and save the document
        $tempPath = Storage::disk($storageDisk)->path($path);
        IOFactory::createWriter($phpWord, 'Word2007')->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }

    /**
     * Build a simple plain text representation of records for template placeholder injection.
     */
    protected function buildPlainTextTable(array $records): string
    {
        if ($records === []) {
            return 'No data available';
        }

        $lines = [];
        foreach ($records as $row) {
            $values = array_map(fn ($v) => (string) ($v ?? '-'), $row);
            $lines[] = implode(" \t ", $values);
        }

        return implode("\n", $lines);
    }
}
