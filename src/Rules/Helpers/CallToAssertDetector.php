<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules\Helpers;

use Nette\Utils\Strings;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPUnit\Framework\TestCase;
use function strtolower;

final class CallToAssertDetector
{
    public static function isCallToAssert(Node $node, Scope $scope): bool
    {
        if (! $node instanceof Node\Expr\MethodCall && ! $node instanceof Node\Expr\StaticCall) {
            return false;
        }

        if ($scope->getClassReflection() === null) {
            return false;
        }

        $phpUnitTestCaseAncestor = $scope->getClassReflection()->getAncestorWithClassName(TestCase::class);
        if ($phpUnitTestCaseAncestor === null) {
            return false;
        }

        return $node->name instanceof Node\Identifier
            && $phpUnitTestCaseAncestor->hasMethod($node->name->toString())
            && Strings::startsWith(strtolower($node->name->toString()), 'assert');
    }
}
