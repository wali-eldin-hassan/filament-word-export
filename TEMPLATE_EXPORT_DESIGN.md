# Template-Based Export Design

## 1. Export Format Types

### A. Table Export (Current)
- Exports data as structured tables
- Good for: Data reports, lists, grids

### B. Content Export (New)
- Exports as formatted paragraphs, headings, rich text
- Good for: Reports, documents, formatted content

### C. Template Export (New)
- Uses pre-defined Word templates with placeholders
- Good for: Letters, forms, certificates, invoices

## 2. Template Processing Capabilities

### PHPWord TemplateProcessor Features:
```php
// Simple variable replacement
$templateProcessor->setValue('name', 'John Doe');
$templateProcessor->setValue('date', '2024-01-15');

// Multiple values at once
$templateProcessor->setValues([
    'company' => 'Acme Corp',
    'address' => '123 Main St',
    'phone' => '555-0123'
]);

// Clone table rows for dynamic data
$templateProcessor->cloneRow('item', count($items));
foreach ($items as $index => $item) {
    $templateProcessor->setValue("item.name#" . ($index + 1), $item['name']);
    $templateProcessor->setValue("item.price#" . ($index + 1), $item['price']);
}

// Clone blocks for repeating sections
$templateProcessor->cloneBlock('employee_block', count($employees));
```

## 3. Template Examples

### HR Letter Template (Template.docx):
```
[COMPANY LOGO]

${date}

${employee_name}
${employee_address}

Dear ${employee_name},

We are pleased to inform you that your ${position} position has been ${status}.

Your new salary will be ${salary} effective ${effective_date}.

${custom_message}

Sincerely,

${manager_name}
${manager_title}
HR Department
```

### Invoice Template:
```
INVOICE #${invoice_number}

Bill To:                    Ship To:
${bill_name}               ${ship_name}
${bill_address}            ${ship_address}

${item_block}
Item: ${item.name}         Qty: ${item.qty}    Price: ${item.price}
${/item_block}

                           Total: ${total}
```

## 4. API Design

### Configuration-Based Export Format:
```php
ExportToWordAction::make()
    ->exportFormat('template') // table, content, template
    ->templatePath('templates/hr-letter.docx')
    ->templateData(function ($record) {
        return [
            'employee_name' => $record->name,
            'position' => $record->position,
            'salary' => $record->salary,
            'date' => now()->format('F j, Y'),
        ];
    });
```

### Content-Based Export:
```php
ExportToWordAction::make()
    ->exportFormat('content')
    ->contentStructure([
        'title' => 'Employee Report',
        'sections' => [
            [
                'heading' => 'Employee Details',
                'content' => function ($record) {
                    return "Name: {$record->name}\nPosition: {$record->position}";
                }
            ]
        ]
    ]);
```

### Flexible Format Selection:
```php
ExportToWordAction::make()
    ->formatOptions([
        'table' => 'Export as Table',
        'content' => 'Export as Document', 
        'template' => 'Use HR Letter Template'
    ])
    ->defaultFormat('table');
```

## 5. Implementation Classes

### New Classes:
1. `ContentExportBuilder` - Handle rich content export
2. `TemplateExportBuilder` - Handle template processing
3. `ExportFormatManager` - Manage different export formats
4. `TemplateRepository` - Manage template files

### Enhanced Classes:
1. `WordExportService` - Support multiple export formats
2. `ExportToWordAction` - Add format selection UI
3. Configuration - Add template settings
