# PHP Compatibility Guide

This guide helps you understand the PHP version requirements and provides solutions for different scenarios.

## Current Requirements

**Filament Word Export Plugin v2.0+** requires:
- **PHP 8.3+**
- **Laravel 10.x or 11.x**
- **Filament 4.x**

## Why PHP 8.3+?

Starting with version 2.0, this plugin requires PHP 8.3+ because:

1. **Filament v4.1.1** requires PHP 8.3+ (via openspout dependency)
2. **openspout/openspout v4.32.0** requires PHP 8.3+
3. **Modern dependencies** (Symfony 7.x, brick/math, etc.) require PHP 8.3+
4. **Better performance** and security features in PHP 8.3+

## Migration Options

### Option 1: Upgrade to PHP 8.3+ (Recommended)

**Benefits:**
- ✅ Access to latest features and security updates
- ✅ Better performance
- ✅ Full compatibility with modern Laravel/Filament ecosystem
- ✅ Long-term support and updates

**Steps:**
1. **Update your server/environment to PHP 8.3**
2. **Update your project dependencies:**
   ```bash
   composer update
   ```
3. **Test your application thoroughly**
4. **Update your deployment scripts/Docker files**

### Option 2: Use Legacy Version (PHP 8.1 Support)

If you must stay on PHP 8.1, you can use the legacy version:

```bash
# Install the last version that supports PHP 8.1
composer require wali/filament-word-export:^1.0
```

**Limitations:**
- ❌ No new features from v2.0+
- ❌ Limited support and updates
- ❌ May have compatibility issues with newer Filament versions
- ❌ Security updates only for critical issues

### Option 3: Fork and Maintain (Advanced Users)

For advanced users who need PHP 8.1 support with new features:

1. **Fork the repository**
2. **Downgrade dependencies** to PHP 8.1 compatible versions:
   ```bash
   # Use Filament v3.x instead of v4.x
   composer require filament/filament:^3.0
   ```
3. **Update code** to work with older dependency versions
4. **Maintain your own version**

## PHP Version Upgrade Guide

### Checking Your Current PHP Version

```bash
# Command line
php -v

# In your application
echo PHP_VERSION;
```

### Common Upgrade Paths

#### Ubuntu/Debian
```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php
sudo apt update

# Install PHP 8.2
sudo apt install php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip

# Switch default PHP version
sudo update-alternatives --set php /usr/bin/php8.2
```

#### CentOS/RHEL
```bash
# Enable Remi repository
sudo dnf install epel-release
sudo dnf install https://rpms.remirepo.net/enterprise/remi-release-8.rpm

# Install PHP 8.2
sudo dnf module enable php:remi-8.2
sudo dnf install php php-cli php-fpm php-mysql php-xml php-mbstring php-curl php-zip
```

#### Docker
```dockerfile
# Use PHP 8.2 base image
FROM php:8.2-fpm

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring xml curl zip
```

#### Shared Hosting
Contact your hosting provider to:
- Enable PHP 8.2 or 8.3
- Update your `.htaccess` or control panel settings
- Verify all required extensions are available

### Testing After Upgrade

1. **Run your test suite:**
   ```bash
   php artisan test
   ```

2. **Check for deprecated features:**
   ```bash
   # Enable error reporting
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```

3. **Verify package compatibility:**
   ```bash
   composer install
   composer audit
   ```

## Troubleshooting

### Common Issues After PHP Upgrade

1. **Extension Missing:**
   ```bash
   # Install missing extensions
   sudo apt install php8.2-[extension-name]
   ```

2. **Permission Issues:**
   ```bash
   # Fix file permissions
   sudo chown -R www-data:www-data /path/to/your/project
   sudo chmod -R 755 /path/to/your/project
   ```

3. **Configuration Issues:**
   ```bash
   # Copy PHP configuration
   sudo cp /etc/php/8.1/cli/php.ini /etc/php/8.2/cli/php.ini
   sudo cp /etc/php/8.1/fpm/php.ini /etc/php/8.2/fpm/php.ini
   ```

### Getting Help

1. **Check the documentation:** [README.md](README.md)
2. **Create an issue:** [GitHub Issues](https://github.com/wali/filament-word-export/issues)
3. **Join discussions:** [GitHub Discussions](https://github.com/wali/filament-word-export/discussions)

## Version Compatibility Matrix

| Plugin Version | PHP Version | Laravel Version | Filament Version |
|---------------|-------------|-----------------|------------------|
| v2.0+         | 8.3+        | 10.x, 11.x      | 4.x              |
| v1.x          | 8.1+        | 9.x, 10.x       | 3.x              |

## Conclusion

We strongly recommend upgrading to PHP 8.3+ to take advantage of:
- Latest security features
- Improved performance
- Better developer experience
- Full ecosystem compatibility
- Long-term support

The upgrade process is usually straightforward, and the benefits far outweigh the effort required.
