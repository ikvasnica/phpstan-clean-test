<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use function sprintf;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Class_>
 */
final class UnitExtendsFromTestCaseRule implements Rule
{
    /** @var array<int, class-string> */
    private $classesAllowedToBeExtendedInTests;

    /** @var string */
    private $unitTestNamespace;

    /**
     * @param array<int, class-string> $classesAllowedToBeExtendedInTests
     */
    public function __construct(array $classesAllowedToBeExtendedInTests, string $unitTestNamespace)
    {
        $this->classesAllowedToBeExtendedInTests = $classesAllowedToBeExtendedInTests;
        $this->unitTestNamespace = $unitTestNamespace;
    }

    public function getNodeType(): string
    {
        return Node\Stmt\Class_::class;
    }

    /**
     * @return array<string>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var Node\Stmt\Class_ $node */
        $node = $node;

        $extendedClass = $node->extends;
        if ($extendedClass === null || ! UnitTestRuleHelper::isUnitTest((string) $scope->getNamespace(), (string) $node->name, $this->unitTestNamespace)) {
            return [];
        }

        if (! in_array($extendedClass->toString(), $this->classesAllowedToBeExtendedInTests, true)) {
            return [
                sprintf(
                    'Extending from the class "%s" is not allowed in unit tests.',
                    $extendedClass
                ),
            ];
        }

        return [];
    }
}
