<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use function in_array;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use function sprintf;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\ClassMethod>
 */
final class DisallowSetupAndConstructorRule implements Rule
{
    /** @var array<string> */
    private const DISALLOWED_METHODS = ['__construct', 'setUp'];

    /** @var string */
    private $unitTestNamespace;

    public function __construct(string $unitTestNamespace)
    {
        $this->unitTestNamespace = $unitTestNamespace;
    }

    public function getNodeType(): string
    {
        return \PhpParser\Node\Stmt\ClassMethod::class;
    }

    /**
     * @return array<string>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var \PhpParser\Node\Stmt\ClassMethod $node */
        $node = $node;

        $classReflection = $scope->getClassReflection();
        if ($classReflection === null || ! UnitTestRuleHelper::isUnitTest((string) $scope->getNamespace(), $classReflection->getName(), $this->unitTestNamespace)) {
            return [];
        }

        $methodName = $node->name->name;
        if (in_array($methodName, self::DISALLOWED_METHODS, true)) {
            return [
                sprintf(
                    'Declaring the method "%s" in unit tests is forbidden.',
                    $methodName
                ),
            ];
        }

        return [];
    }
}
