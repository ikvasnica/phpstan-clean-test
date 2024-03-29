<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use ikvasnica\PHPStan\Rules\Helpers\TestClassDetector;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use function sprintf;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Class_>
 */
final class UnitExtendsFromTestCaseRule implements Rule
{
    /**
     * @var array<int, string>
     */
    private $classesAllowedToBeExtendedInTests;

    /**
     * @var string
     */
    private $unitTestNamespaceContainsString;

    /**
     * @param array<int, string> $classesAllowedToBeExtendedInTests
     */
    public function __construct(array $classesAllowedToBeExtendedInTests, string $unitTestNamespaceContainsString)
    {
        $this->classesAllowedToBeExtendedInTests = $classesAllowedToBeExtendedInTests;
        $this->unitTestNamespaceContainsString = $unitTestNamespaceContainsString;
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
        if ($extendedClass === null || ! TestClassDetector::isUnitTest((string) $scope->getNamespace(), (string) $node->name, $this->unitTestNamespaceContainsString)) {
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
