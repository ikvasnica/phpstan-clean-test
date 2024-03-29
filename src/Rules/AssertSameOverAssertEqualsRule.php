<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use ikvasnica\PHPStan\Rules\Helpers\CallToAssertDetector;
use PhpParser\Node;
use PhpParser\NodeAbstract;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Type;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\NodeAbstract>
 */
final class AssertSameOverAssertEqualsRule implements Rule
{
    public function getNodeType(): string
    {
        return NodeAbstract::class;
    }

    /**
     * @return array<string>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (! CallToAssertDetector::isCallToAssert($node, $scope)) {
            return [];
        }

        /** @var Node\Expr\MethodCall|Node\Expr\StaticCall $node */
        $node = $node;

        if (! $node->name instanceof Node\Identifier || count($node->args) < 2 || strtolower($node->name->toString()) !== 'assertequals') {
            return [];
        }

        $firstType = $scope->getType($node->getArgs()[0]->value);
        $secondType = $scope->getType($node->getArgs()[1]->value);

        if ($this->isScalarType($firstType) || $this->isScalarType($secondType)) {
            return ['Using "assertEquals" is forbidden with a scalar type as an argument. Use "assertSame" instead.'];
        }

        return [];
    }

    private function isScalarType(Type\Type $type): bool
    {
        return $type instanceof Type\ConstantScalarType
            || $type instanceof Type\StringType
            || $type instanceof Type\BooleanType
            || $type instanceof Type\FloatType
            || $type instanceof Type\IntegerType;
    }
}
