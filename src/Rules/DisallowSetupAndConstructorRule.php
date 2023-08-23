<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use ikvasnica\PHPStan\Rules\Helpers\TestClassDetector;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use function in_array;
use function sprintf;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\ClassMethod>
 */
final class DisallowSetupAndConstructorRule implements Rule
{
    /**
     * @var string
     */
    private const SETUP_METHOD_NAME = 'setUp';

    /**
     * @var array<string>
     */
    private const DISALLOWED_METHODS = ['__construct', self::SETUP_METHOD_NAME];

    /**
     * @var string
     */
    private $unitTestNamespaceContainsString;

    /**
     * @var bool
     */
    private $allowSetupInUnitTests;

    public function __construct(string $unitTestNamespaceContainsString, bool $allowSetupInUnitTests)
    {
        $this->unitTestNamespaceContainsString = $unitTestNamespaceContainsString;
        $this->allowSetupInUnitTests = $allowSetupInUnitTests;
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
        if ($classReflection === null || ! TestClassDetector::isUnitTest((string) $scope->getNamespace(), $classReflection->getName(), $this->unitTestNamespaceContainsString)) {
            return [];
        }

        $methodName = $node->name->name;
        if ($this->isMethodForbidden($methodName)) {
            return [
                sprintf(
                    'Declaring the method "%s" in unit tests is forbidden.',
                    $methodName
                ),
            ];
        }

        return [];
    }

    private function isMethodForbidden(string $methodName): bool
    {
        if ($this->allowSetupInUnitTests && $methodName === self::SETUP_METHOD_NAME) {
            return false;
        }

        return in_array($methodName, self::DISALLOWED_METHODS, true);
    }
}
