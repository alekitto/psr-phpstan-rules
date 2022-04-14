<?php

declare(strict_types=1);

namespace Kcs\PsrPhpstan\Container;

use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ParametersAcceptorSelector;
use PHPStan\Reflection\ReflectionProviderStaticAccessor;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use Psr\Container\ContainerInterface;

class ContainerDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return ContainerInterface::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'get';
    }

    public function getTypeFromMethodCall(MethodReflection $methodReflection, MethodCall $methodCall, Scope $scope): ?Type
    {
        if (count($methodCall->args) === 0) {
            return ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())->getReturnType();
        }

        $arg = $methodCall->args[0]->value;
        // Care only for ::class parameters, we can not guess types for random strings.
        if ($arg instanceof ClassConstFetch) {
            $value = $scope->getType($methodCall->args[0]->value)->getValue();
        } elseif ($arg instanceof String_) {
            $value = $arg->value;
        } else {
            return ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())->getReturnType();
        }

        $broker = ReflectionProviderStaticAccessor::getInstance();
        if (! $broker->hasClass($value)) {
            return ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())->getReturnType();
        }

        $classReflection = $broker->getClass($value);
        if (! $classReflection->isClass() && ! $classReflection->isInterface()) {
            return ParametersAcceptorSelector::selectSingle($methodReflection->getVariants())->getReturnType();
        }

        return new ObjectType($value);
    }
}
