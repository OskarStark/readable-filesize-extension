# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Twig extension library that converts file sizes (in bytes) to human-readable strings (e.g., `1024` → `"1 KB"`). The library is simple with a single extension class that provides one Twig filter.

## Architecture

- **src/ReadableFilesizeExtension.php**: The main extension class that provides the `readable_filesize` Twig filter
  - Takes bytes (float|int) and optional precision (int, default 2)
  - Returns formatted string with appropriate unit (B, KB, MB, GB, TB, PB, EB)
  - Uses webmozart/assert for validation (bytes must be >= 0)
- **tests/ReadableFilesizeExtensionTest.php**: PHPUnit tests using PHP 8 attributes and ergebnis/data-provider

## Development Commands

### Running Tests
```bash
make test                           # Run all tests
vendor/bin/phpunit                  # Run PHPUnit directly
vendor/bin/phpunit --filter=<name>  # Run specific test
```

### Code Quality
```bash
make cs                            # Fix coding standards with PHP-CS-Fixer
make phpstan                       # Run static analysis with PHPStan
vendor/bin/php-cs-fixer fix --diff --dry-run  # Check CS without fixing
```

### Dependencies
```bash
make vendor                        # Install/update dependencies (uses symfony composer)
```

## Code Standards

- **PHP Version**: Requires PHP 8.1+
- **Coding Standards**: Uses ergebnis/php-cs-fixer-config with PHP 8.1 ruleset
  - File headers with copyright notice required
  - Concat spacing: no spaces (`$a.$b` not `$a . $b`)
  - Native function invocation with @compiler_optimized
- **Static Analysis**: PHPStan level max with baseline file
- **Testing**: PHPUnit 10.5 with PHP 8 attributes (#[Test], #[DataProvider], etc.)

## Testing Conventions

- Use PHP 8 attributes instead of annotations
- Use `self::assertSame()` for strict equality checks
- Use `ergebnis/data-provider` for common test data (e.g., IntProvider::lessThanZero)
- Test data providers return `\Generator` with documented return types
