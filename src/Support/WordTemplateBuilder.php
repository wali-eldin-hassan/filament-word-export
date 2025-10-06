<?php

namespace Wali\FilamentWordExport\Support;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\Element\Section;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\SimpleType\Jc;

class WordTemplateBuilder
{
    protected array $config;

    public function __construct(array $config = [], protected array $overrides = [])
    {
        $this->config = $config ?: config('filament-word-export', []);
    }

    /**
     * Apply headers and footers to the document based on configuration.
     */
    public function applyTemplate(PhpWord $phpWord): void
    {
        $sections = $phpWord->getSections();
        if (empty($sections)) {
            return;
        }

        $section = $sections[0];

        $this->applyHeader($section);
        $this->applyFooter($section);
    }

    /**
     * Apply header configuration to the section.
     */
    protected function applyHeader(Section $section): void
    {
        $headerConfig = $this->getConfig('header', []);

        if (! $this->getConfig('header.enabled', true)) {
            return;
        }

        $header = $section->addHeader();

        // Add logo if configured
        if ($this->getConfig('header.logo.enabled', false)) {
            $this->addLogo($header, $headerConfig['logo'] ?? []);
        }

        // Add header text if configured
        $headerText = $this->getConfig('header.text');
        if ($headerText) {
            $this->addHeaderText($header, $headerText, $headerConfig['style'] ?? []);
        }
    }

    /**
     * Apply footer configuration to the section.
     */
    protected function applyFooter(Section $section): void
    {
        $footerConfig = $this->getConfig('footer', []);

        if (! $this->getConfig('footer.enabled', true)) {
            return;
        }

        $footer = $section->addFooter();

        // Add footer text if configured
        $footerText = $this->getConfig('footer.text');
        if ($footerText) {
            $this->addFooterText($footer, $footerText, $footerConfig['style'] ?? []);
        }

        // Add page numbers if configured
        if ($this->getConfig('footer.show_page_numbers', false)) {
            $this->addPageNumbers($footer, $footerConfig);
        }
    }

    /**
     * Add logo to header.
     */
    protected function addLogo($header, array $logoConfig): void
    {
        $logoPath = $logoConfig['path'] ?? null;
        if (! $logoPath) {
            return;
        }

        $disk = Storage::disk($this->getConfig('storage_disk', 'local'));
        if (! $disk->exists($logoPath)) {
            return;
        }

        $fullPath = $disk->path($logoPath);
        $width = Converter::pixelToTwip($logoConfig['width'] ?? 100);
        $height = Converter::pixelToTwip($logoConfig['height'] ?? 50);

        $alignment = $this->convertAlignment($logoConfig['alignment'] ?? 'left');

        $header->addImage($fullPath, [
            'width' => $width,
            'height' => $height,
            'alignment' => $alignment,
        ]);
    }

    /**
     * Add text to header.
     */
    protected function addHeaderText($header, string $text, array $style): void
    {
        $textStyle = $this->buildTextStyle($style);
        $alignment = $this->convertAlignment($style['alignment'] ?? 'center');

        $header->addText($text, $textStyle, ['alignment' => $alignment]);
    }

    /**
     * Add text to footer.
     */
    protected function addFooterText($footer, string $text, array $style): void
    {
        $textStyle = $this->buildTextStyle($style);
        $alignment = $this->convertAlignment($style['alignment'] ?? 'center');

        $footer->addText($text, $textStyle, ['alignment' => $alignment]);
    }

    /**
     * Add page numbers to footer.
     */
    protected function addPageNumbers($footer, array $footerConfig): void
    {
        $format = $this->getConfig('footer.page_number_format', 'Page {PAGE} of {NUMPAGES}');
        $alignment = $this->convertAlignment($this->getConfig('footer.page_number_alignment', 'right'));

        $footer->addPreserveText($format, null, ['alignment' => $alignment]);
    }

    /**
     * Build text style array from configuration.
     */
    protected function buildTextStyle(array $style): array
    {
        $textStyle = [];

        if (isset($style['size'])) {
            $textStyle['size'] = $style['size'];
        }

        if (isset($style['color'])) {
            $textStyle['color'] = $style['color'];
        }

        if (isset($style['italic']) && $style['italic']) {
            $textStyle['italic'] = true;
        }

        if (isset($style['bold']) && $style['bold']) {
            $textStyle['bold'] = true;
        }

        return $textStyle;
    }

    /**
     * Convert alignment string to PhpWord constant.
     */
    protected function convertAlignment(string $alignment): string
    {
        return match (strtolower($alignment)) {
            'left' => Jc::START,
            'center' => Jc::CENTER,
            'right' => Jc::END,
            default => Jc::CENTER,
        };
    }

    /**
     * Get configuration value with override support.
     */
    protected function getConfig(string $key, $default = null)
    {
        // Check overrides first
        if (array_key_exists($key, $this->overrides)) {
            return $this->overrides[$key];
        }

        // Use dot notation to get nested config values
        $keys = explode('.', $key);
        $value = $this->config;

        foreach ($keys as $k) {
            if (! is_array($value) || ! array_key_exists($k, $value)) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    /**
     * Static method for backward compatibility.
     *
     * @deprecated Use the new instance-based approach instead
     */
    public static function applyBranding(PhpWord $phpWord): void
    {
        $builder = new static;
        $builder->applyTemplate($phpWord);
    }

    /**
     * Create a new instance with configuration.
     */
    public static function make(array $config = [], array $overrides = []): static
    {
        return new static($config, $overrides);
    }
}
