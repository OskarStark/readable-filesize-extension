<?php

declare(strict_types=1);

/**
 * This file is part of oskarstark/readable-filesize-extension.
 *
 * (c) Oskar Stark <oskarstark@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OskarStark\Twig\Tests;

use Ergebnis\DataProvider\IntProvider;
use OskarStark\Twig\ReadableFilesizeExtension;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class ReadableFilesizeExtensionTest extends TestCase
{
    #[Test]
    public function extendAbstractExtension(): void
    {
        $extension = new ReadableFilesizeExtension();

        self::assertInstanceOf(AbstractExtension::class, $extension);
    }

    #[Test]
    public function isFinal(): void
    {
        $extension = new ReadableFilesizeExtension();
        $reflection = new \ReflectionClass($extension);

        self::assertTrue($reflection->isFinal());
    }

    #[Test]
    public function numberOfFilters(): void
    {
        $extension = new ReadableFilesizeExtension();

        self::assertCount(1, $extension->getFilters());
    }

    #[Test]
    #[Depends('numberOfFilters')]
    public function filters(): void
    {
        $extension = new ReadableFilesizeExtension();

        $functions = $extension->getFilters();

        $filter = $functions[0];
        self::assertInstanceOf(TwigFilter::class, $filter);
        self::assertSame('readable_filesize', $filter->getName());
    }

    #[Test]
    #[DataProviderExternal(IntProvider::class, 'lessThanZero')]
    public function readableThrowsExceptionOn(int $value): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(\sprintf(
            'Expected a value greater than or equal to 0. Got: %s',
            $value,
        ));

        $extension = new ReadableFilesizeExtension();
        $extension->readableFilesize($value);
    }

    #[Test]
    #[DataProvider('readableFilesizeProvider')]
    public function readableFilesize(string $expected, int $precision, float|int $value): void
    {
        $extension = new ReadableFilesizeExtension();

        self::assertSame(
            $expected,
            $extension->readableFilesize($value, $precision),
        );
    }

    /**
     * @return \Generator<array{0: non-empty-string, 1: int, 2: positive-int}>
     */
    public static function readableFileSizeProvider(): iterable
    {
        yield ['1 B', 0, 1];
        yield ['1 KB', 0, 1024];
        yield ['1 MB', 0, 1024 * 1024];
        yield ['1 GB', 0, 1024 * 1024 * 1024];
        yield ['1 TB', 0, 1024 * 1024 * 1024 * 1024];
        yield ['1 PB', 0, 1024 * 1024 * 1024 * 1024 * 1024];
        yield ['1 EB', 0, 1024 * 1024 * 1024 * 1024 * 1024 * 1024];

        // Precision 1
        yield ['1.5 KB', 1, 1536];
        yield ['2.5 KB', 1, 2560];
        yield ['1.5 MB', 1, 1572864];
        yield ['2.5 MB', 1, 2621440];
        yield ['1.5 GB', 1, 1610612736];

        // Precision 2
        yield ['1.25 KB', 2, 1280];
        yield ['2.75 KB', 2, 2816];
        yield ['1.25 MB', 2, 1310720];
        yield ['2.75 MB', 2, 2883584];
        yield ['1.25 GB', 2, 1342177280];
    }
}
