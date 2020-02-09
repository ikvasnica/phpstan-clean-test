<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use ikvasnica\PHPStan\Rules\Helpers\CallToAssertDetector;
use PhpParser\Node;
use PhpParser\NodeAbstract;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPUnit\Framework\Assert;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\NodeAbstract>
 */
final class StaticAssertOverThisAndStaticRule implements Rule
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
        assert($node->name instanceof Node\Identifier);

        if (! $node instanceof Node\Expr\StaticCall) {
            return [$this->getErrorMessage('$this->', $node->name->toString())];
        }

        if ($node->class instanceof Node\Name && $node->class->toString() !== Assert::class) {
            return [$this->getErrorMessage(sprintf('%s::', $node->class->toString()), $node->name->toString())];
        }

        return [];
    }

    private function getErrorMessage(string $calledWithString, string $methodName): string
    {
        return sprintf('Calling %s%s is forbidden. Use %s::%s instead.', $calledWithString, $methodName, Assert::class, $methodName);
    }
}
