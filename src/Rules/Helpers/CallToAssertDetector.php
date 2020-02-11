<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules\Helpers;

use Nette\Utils\Strings;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPUnit\Framework\TestCase;

final class CallToAssertDetector
{
    public static function isCallToAssert(Node $node, Scope $scope): bool
    {
        if (! $node instanceof Node\Expr\MethodCall && ! $node instanceof Node\Expr\StaticCall) {
            return false;
        }

        if ($scope->getClassReflection() === null || ! in_array(TestCase::class, $scope->getClassReflection()->getParentClassesNames(), true)) {
            return false;
        }

        return $node->name instanceof Node\Identifier
            && Strings::startsWith(strtolower($node->name->toString()), 'assert');
    }
}
