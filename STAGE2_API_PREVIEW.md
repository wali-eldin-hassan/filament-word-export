# Stage 2: Document Customization API Preview

## Fluent API Usage Examples

### Basic Page Setup
```php
ExportToWordAction::make()
    ->pageSize('A4')
    ->orientation('landscape')
    ->margins(top: 1440, bottom: 1440, left: 1440, right: 1440)
    ->documentTitle('Sales Report')
    ->documentSubject('Q4 2024 Sales Data');
```

### Advanced Styling
```php
ExportToWordAction::make()
    ->pageSize('Letter')
    ->defaultFont('Arial', 12)
    ->tableStyle('modern')
    ->alternatingRows(true, 'F2F2F2')
    ->watermark('CONFIDENTIAL', opacity: 0.3, rotation: 45);
```

### Security Features
```php
ExportToWordAction::make()
    ->passwordProtect('secret123')
    ->documentProtection('readOnly', 'admin456')
    ->documentProperties([
        'title' => 'Confidential Report',
        'company' => 'Acme Corp',
        'creator' => 'John Doe'
    ]);
```

## Configuration Structure

### Enhanced Config File
```php
return [
    // Existing header/footer config...
    
    'page_setup' => [
        'size' => 'A4',
        'orientation' => 'portrait',
        'margins' => [
            'top' => 1440,
            'bottom' => 1440, 
            'left' => 1440,
            'right' => 1440,
        ],
    ],
    
    'document_properties' => [
        'title' => null,
        'subject' => null,
        'creator' => 'Filament Word Export Plugin',
        'company' => null,
    ],
    
    'typography' => [
        'default_font' => [
            'name' => 'Calibri',
            'size' => 11,
        ],
        'table_font' => [
            'name' => 'Calibri', 
            'size' => 10,
        ],
    ],
    
    'table_styling' => [
        'style' => 'modern',
        'header_background' => '4472C4',
        'alternating_rows' => true,
        'alternating_color' => 'F2F2F2',
    ],
    
    'watermark' => [
        'enabled' => false,
        'text' => 'DRAFT',
        'opacity' => 0.3,
        'rotation' => 45,
    ],
];
```

## Implementation Classes

### New Classes to Create
1. `DocumentPropertiesBuilder` - Handle metadata
2. `PageSetupBuilder` - Handle page configuration  
3. `SecurityBuilder` - Handle protection features
4. `WatermarkBuilder` - Handle watermark features
5. `TypographyBuilder` - Handle font/styling
6. `TableStyleBuilder` - Enhanced table styling

### Enhanced Existing Classes
1. `WordTemplateBuilder` - Integrate new builders
2. `WordExportService` - Add fluent API methods
3. `ExportToWordAction` - Add new configuration methods
