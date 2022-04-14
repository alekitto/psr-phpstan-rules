<?php

declare(strict_types=1);

use Kcs\PsrPhpstan\Tests\Fixtures\ExampleServiceInterface;
use Kcs\PsrPhpstan\Tests\Fixtures\ExampleServiceTrait;
use Kcs\PsrPhpstan\Tests\Fixtures\NonService;
use Kcs\PsrPhpstan\Tests\Fixtures\ExampleServiceClass;
use Psr\Container\ContainerInterface;

use function PHPStan\Testing\assertType;

function (ContainerInterface $container) {
    assertType('mixed', $container->get('unknown'));
    assertType('mixed', $container->get(UnknownClass::class));
    assertType('mixed', $container->get(NonService::class));
    assertType('mixed', $container->get('Kcs\PsrPhpstan\Tests\Fixtures\NonService'));
    assertType('mixed', $container->get(ExampleServiceTrait::class));
    assertType('mixed', $container->get('Kcs\PsrPhpstan\Tests\Fixtures\ExampleServiceTrait'));

    assertType('stdClass', $container->get('stdClass'));
    assertType('stdClass', $container->get(stdClass::class));
    assertType('Kcs\PsrPhpstan\Tests\Fixtures\ExampleServiceClass', $container->get(ExampleServiceClass::class));
    assertType('Kcs\PsrPhpstan\Tests\Fixtures\ExampleServiceClass', $container->get('Kcs\PsrPhpstan\Tests\Fixtures\ExampleServiceClass'));
    assertType('Kcs\PsrPhpstan\Tests\Fixtures\ExampleServiceInterface', $container->get(ExampleServiceInterface::class));
    assertType('Kcs\PsrPhpstan\Tests\Fixtures\ExampleServiceInterface', $container->get('Kcs\PsrPhpstan\Tests\Fixtures\ExampleServiceInterface'));
};
