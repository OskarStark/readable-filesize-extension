# readable-filesize-extension

A Twig extension to convert filesize to human readable string.

[![CI][ci_badge]][ci_link]

## Installation

```
composer require oskarstark/readable-filesize-extension
```

```yaml
# config/services.yaml
services:
    OskarStark\Twig\ReadableFilesizeExtension:
        tags: ['twig.extension']
```

## Usage

```twig
// file.size = 1024

{{ file.size|readable_filesize() }} # prints '1 KB'
{{ file.size|readable_filesize(2) }} # prints '1.00 KB'
```

[ci_badge]: https://github.com/OskarStark/readable-filesize-extension/workflows/CI/badge.svg?branch=main
[ci_link]: https://github.com/OskarStark/readable-filesize-extension/actions?query=workflow:ci+branch:main
