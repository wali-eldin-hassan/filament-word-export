<?php

namespace Wali\FilamentWordExport\Services;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
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
        $phpWord = new PhpWord;
        $section = $phpWord->addSection();

        // Apply headers and footers using the template builder
        $templateBuilder = WordTemplateBuilder::make($this->config, $this->templateOverrides);
        $templateBuilder->applyTemplate($phpWord);

        // Create the data table
        $this->createDataTable($section, $records);

        // Generate and return the file
        return $this->generateFile($phpWord, $options);
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
    protected function generateFile(PhpWord $phpWord, array $options): BinaryFileResponse
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
}
