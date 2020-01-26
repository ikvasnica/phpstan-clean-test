<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use Nette\Utils\Strings;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use function sprintf;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\Class_>
 */
final class UnitExtendsFromTestCaseRule implements Rule
{
    /** @var string */
    private const TEST_CLASS_ENDING_STRING = 'Test';

    /** @var array<int, class-string> */
    private $classesAllowedToBeExtendedInTests;

    /** @var string */
    private $unitTestNamespaceContainsString;

    /**
     * @param array<int, class-string> $classesAllowedToBeExtendedInTests
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
        if ($extendedClass === null || $scope->getNamespace() === null || ! $this->isUnitTest($scope->getNamespace(), (string) $node->name)) {
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

    private function isUnitTest(string $namespace, string $nodeName): bool
    {
        return Strings::contains($namespace, $this->unitTestNamespaceContainsString)
            && Strings::endsWith($nodeName, self::TEST_CLASS_ENDING_STRING);
    }
}
