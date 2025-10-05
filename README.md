# Filament Word Export Plugin

A Filament plugin for exporting table data to Microsoft Word (DOCX) format.

## Installation

You can install the package via composer:

```bash
composer require wali/filament-word-export
```

## Usage

Add the export action to your Filament table:

```php
use Wali\FilamentWordExport\Actions\ExportToWordAction;

public function table(Table $table): Table
{
    return $table
        ->columns([
            // Your table columns
        ])
        ->bulkActions([
            ExportToWordAction::make(),
        ]);
}
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag="filament-word-export-config"
```

## Development

This project uses Laravel Pint for code formatting and Rector for automated refactoring.

### Code Formatting with Pint

```bash
# Check code style
composer lint:test

# Fix code style issues
composer lint

# Fix only dirty files (git)
composer lint:dirty
```

### Code Refactoring with Rector

```bash
# Preview changes (dry run)
composer refactor:dry

# Apply refactoring changes
composer refactor
```

### Combined Commands

```bash
# Check formatting and preview refactoring
composer format

# Apply both formatting and refactoring
composer fix
```

### Manual Tool Usage

You can also run the tools directly:

```bash
# Pint
./vendor/bin/pint
./vendor/bin/pint --test
./vendor/bin/pint --dirty

# Rector
./vendor/bin/rector process
./vendor/bin/rector process --dry-run
```

## Configuration Files

- `pint.json` - Laravel Pint configuration
- `rector.php` - Rector configuration
- `config/filament-word-export.php` - Plugin configuration

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
