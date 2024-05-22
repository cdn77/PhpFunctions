<?php

declare(strict_types=1);

namespace Cdn77\Functions\PHPStan;

use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Type\Type;

use function assert;

final class AssertReturnDynamicFunctionReturnTypeExtension implements
    DynamicFunctionReturnTypeExtension,
    TypeSpecifierAwareExtension
{
    private TypeSpecifier $typeSpecifier;

    public function isFunctionSupported(FunctionReflection $functionReflection): bool
    {
        return $functionReflection->getName() === 'Cdn77\Functions\assert_return';
    }

    public function getTypeFromFunctionCall(
        FunctionReflection $functionReflection,
        FuncCall $functionCall,
        Scope $scope,
    ): Type|null {
        $arg1 = $functionCall->getArgs()[1]->value;
        assert($arg1 instanceof FuncCall, 'Second argument of assert_return must be a function call');

        $call = new FuncCall($arg1->name, [$functionCall->getArgs()[0]], $arg1->getAttributes());

        $specifiedTypes = $this->typeSpecifier->specifyTypesInCondition(
            $scope,
            $call,
            TypeSpecifierContext::createTruthy(),
        );

        return $specifiedTypes->getSureTypes()['$value'][1] ?? null;
    }

    public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
    {
        $this->typeSpecifier = $typeSpecifier;
    }
}
