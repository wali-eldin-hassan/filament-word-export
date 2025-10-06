<?php

namespace Wali\FilamentWordExport\Actions;

use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Wali\FilamentWordExport\Services\WordExportService;

class ExportToWordAction extends BulkAction
{
    protected array $templateOverrides = [];
    protected array $exportOptions = [];

    public static function getDefaultName(): ?string
    {
        return 'exportToWord';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Export to Word')
            ->icon('heroicon-o-document-text')
            ->requiresConfirmation()
            ->action(function (Collection $records) {
                $data = $records->map(fn ($record) => $record->toArray())->toArray();

                $service = app(WordExportService::class);

                if ($this->templateOverrides !== []) {
                    $service->withTemplateOverrides($this->templateOverrides);
                }

                return $service->export($data, $this->exportOptions);
            });
    }

    /**
     * Set template configuration overrides for this action.
     */
    public function templateOverrides(array $overrides): static
    {
        $this->templateOverrides = array_merge($this->templateOverrides, $overrides);

        return $this;
    }

    /**
     * Set export options for this action.
     */
    public function exportOptions(array $options): static
    {
        $this->exportOptions = array_merge($this->exportOptions, $options);

        return $this;
    }

    /**
     * Disable header for this action.
     */
    public function withoutHeader(): static
    {
        return $this->templateOverrides(['header.enabled' => false]);
    }

    /**
     * Disable footer for this action.
     */
    public function withoutFooter(): static
    {
        return $this->templateOverrides(['footer.enabled' => false]);
    }

    /**
     * Set custom header text for this action.
     */
    public function headerText(string $text): static
    {
        return $this->templateOverrides(['header.text' => $text]);
    }

    /**
     * Set custom footer text for this action.
     */
    public function footerText(string $text): static
    {
        return $this->templateOverrides(['footer.text' => $text]);
    }

    /**
     * Set custom filename for this action.
     */
    public function filename(string $filename): static
    {
        return $this->exportOptions(['filename' => $filename]);
    }

    /**
     * Enable page numbers in footer for this action.
     */
    public function withPageNumbers(bool $enabled = true, string $format = 'Page {PAGE} of {NUMPAGES}'): static
    {
        return $this->templateOverrides([
            'footer.show_page_numbers' => $enabled,
            'footer.page_number_format' => $format,
        ]);
    }
}
