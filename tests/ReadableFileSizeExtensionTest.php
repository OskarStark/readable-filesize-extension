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

use Ergebnis\Test\Util\Helper;
use OskarStark\Twig\ReadableFilesizeExtension;
use PHPUnit\Framework\TestCase;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class ReadableFileSizeExtensionTest extends TestCase
{
    use Helper;

    /**
     * @test
     */
    public function extendAbstractExtension(): void
    {
        self::assertClassExtends(
            AbstractExtension::class,
            ReadableFilesizeExtension::class,
        );
    }

    /**
     * @test
     */
    public function isFinal(): void
    {
        self::assertClassIsFinal(ReadableFilesizeExtension::class);
    }

    /**
     * @test
     */
    public function numberOfFilters(): void
    {
        $extension = new ReadableFilesizeExtension();

        self::assertCount(1, $extension->getFilters());
    }

    /**
     * @test
     *
     * @depends numberOfFilters
     */
    public function filters(): void
    {
        $extension = new ReadableFilesizeExtension();

        $functions = $extension->getFilters();

        $filter = $functions[0];
        self::assertInstanceOf(TwigFilter::class, $filter);
        self::assertSame('readable_filesize', $filter->getName());
    }

    /**
     * @test
     *
     * @dataProvider \Ergebnis\Test\Util\DataProvider\IntProvider::lessThanZero()
     */
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

    /**
     * @test
     *
     * @dataProvider readableFilesizeProvider
     */
    public function readableFilesize(string $expected, int $precision, float|int $value): void
    {
        $extension = new ReadableFilesizeExtension();

        self::assertSame(
            $expected,
            $extension->readableFilesize($value, $precision),
        );
    }

    /**
     * @return \Generator<array{0: string, 1: int, 2: int}>
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
    }
}
