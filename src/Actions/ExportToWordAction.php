<?php

namespace Wali\FilamentWordExport\Actions;

use Illuminate\Database\Eloquent\Collection;
use Wali\FilamentWordExport\Actions\Concerns\FilamentBulkActionBase;
use Wali\FilamentWordExport\Services\WordExportService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;

class ExportToWordAction extends FilamentBulkActionBase
{
    protected array $templateOverrides = [];
    protected array $exportOptions = [];
    protected bool $allowCustomTemplateUpload = false;
    protected bool $allowCustomVariables = false;

    /**
     * Directory (relative to storage disk) where uploaded templates will be stored.
     */
    protected string $customTemplateDirectory = 'word-templates';

    /**
     * Enable end-user ability to upload a custom Word (.docx) template for this export.
     */
    public function allowCustomTemplateUpload(bool $enable = true, ?string $directory = null): static
    {
        $this->allowCustomTemplateUpload = $enable;
        if ($directory) {
            $this->customTemplateDirectory = $directory;
        }

        return $this;
    }

    /**
     * Allow user to specify arbitrary placeholder => value pairs for TemplateProcessor.
     */
    public function allowCustomVariables(bool $enable = true): static
    {
        $this->allowCustomVariables = $enable;

        return $this;
    }

    public static function getDefaultName(): ?string
    {
        return 'exportToWord';
    }

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Export to Word')
            ->icon('heroicon-o-document-text')
            ->requiresConfirmation();

        // Inject optional form for custom template upload
        $formComponents = [];
        if ($this->allowCustomTemplateUpload) {
            $formComponents[] = FileUpload::make('custom_template')
                ->label('Custom Template (.docx)')
                ->helperText('Optional: upload a .docx template to use instead of the default generated layout.')
                ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                ->directory($this->customTemplateDirectory)
                ->preserveFilenames()
                ->maxSize(5 * 1024) // 5 MB
                ->columnSpanFull();
        }
        if ($this->allowCustomVariables) {
            $formComponents[] = KeyValue::make('template_variables')
                ->label('Template Variables')
                ->helperText('Add placeholder => value pairs. Placeholders should match ${placeholder} in the .docx template (omit ${} here).')
                ->addButtonLabel('Add Variable')
                ->columnSpanFull();
        }
        if ($formComponents !== []) {
            $this->form($formComponents);
        }

        $this->action(function (Collection $records, array $data = []) {
            $tableData = $records->map(fn ($record) => $record->toArray())->toArray();

            // If user uploaded a custom template, record its relative storage path in export options
            if ($this->allowCustomTemplateUpload && ! empty($data['custom_template'])) {
                // Single file upload returns string path
                $this->exportOptions(['custom_template_path' => $data['custom_template']]);
            }
            if ($this->allowCustomVariables && ! empty($data['template_variables']) && is_array($data['template_variables'])) {
                $this->exportOptions(['custom_template_variables' => $data['template_variables']]);
            }

            $service = app(WordExportService::class);

            if ($this->templateOverrides !== []) {
                $service->withTemplateOverrides($this->templateOverrides);
            }

            return $service->export($tableData, $this->exportOptions);
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
