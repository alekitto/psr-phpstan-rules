<?php

namespace Kcs\PsrPhpstan\Tests\Container;

use PHPStan\Testing\TypeInferenceTestCase;

class ContainerDynamicReturnTypeExtensionTest extends TypeInferenceTestCase
{
    /**
     * @dataProvider dataFileAsserts
     * @param string $assertType
     * @param string $file
     * @param mixed ...$args
     */
    public function testFileAsserts(
        string $assertType,
        string $file,
               ...$args
    ): void
    {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    public function dataFileAsserts(): iterable
    {
        yield from $this->gatherAssertTypes(__DIR__ . '/../data/container.php');
    }

    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../../phpstan.neon',
        ];
    }
}
