<?php declare(strict_types = 1);

namespace Cdn77\Functions\PHPStan;

use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\Analyser\SpecifiedTypes;
use PHPStan\Analyser\TypeSpecifier;
use PHPStan\Analyser\TypeSpecifierAwareExtension;
use PHPStan\Analyser\TypeSpecifierContext;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\FunctionTypeSpecifyingExtension;

class AssertReturnFunctionTypeSpecifyingExtension implements FunctionTypeSpecifyingExtension, TypeSpecifierAwareExtension
{
    private TypeSpecifier $typeSpecifier;

    public function isFunctionSupported(FunctionReflection $functionReflection, FuncCall $node, TypeSpecifierContext $context): bool
    {
        return $functionReflection->getName() === 'Cdn77\Functions\assert_return';
//            && isset($node->getArgs()[0]);
    }

    public function specifyTypes(FunctionReflection $functionReflection, FuncCall $node, Scope $scope, TypeSpecifierContext $context): SpecifiedTypes
    {
        $arg1 = $node->getArgs()[1]->value;
        assert($arg1 instanceof FuncCall, 'Second argument of assert_return must be a function call');
        $new = new \PhpParser\Node\Expr\FuncCall($arg1->name, [$node->getArgs()[0]], $arg1->getAttributes());
        return $this->typeSpecifier->specifyTypesInCondition($scope, $new, TypeSpecifierContext::createTruthy());
    }

    public function setTypeSpecifier(TypeSpecifier $typeSpecifier): void
    {
        $this->typeSpecifier = $typeSpecifier;
    }

}
