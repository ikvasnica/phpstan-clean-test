<?php

declare(strict_types=1);

namespace ikvasnica\PHPStan\Rules;

use ikvasnica\PHPStan\Rules\Helpers\TestClassDetector;
use Nette\Utils\Strings;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Rules\Rule;
use function sprintf;

/**
 * @implements \PHPStan\Rules\Rule<\PhpParser\Node\Stmt\ClassMethod>
 */
final class NoNullableArgumentRule implements Rule
{
    /** @var string */
    private const TEST_ANNOTATION = '@test';

    /** @var string */
    private const TEST_METHOD_NAME_BEGINNING = 'test';

    public function getNodeType(): string
    {
        return Node\Stmt\ClassMethod::class;
    }

    /**
     * @return array<string>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        /** @var Node\Stmt\ClassMethod $node */
        $node = $node;

        $classReflection = $scope->getClassReflection();
        if ($classReflection === null || ! $this->isTestMethod($node, $classReflection)) {
            return [];
        }

        $errors = [];

        /** @var \PhpParser\Node\Param $testMethodArgument */
        foreach ($node->getParams() as $testMethodArgument) {
            if ($testMethodArgument->type === null) {
                $errors[] = $this->getErrorWithMessage(
                    $testMethodArgument,
                    $node,
                    'Argument "%s" of the method "%s" has no type. Arguments with no type and nullable arguments in test methods are forbidden.'
                );
            } elseif ($testMethodArgument->type instanceof Node\NullableType) {
                $errors[] = $this->getErrorWithMessage(
                    $testMethodArgument,
                    $node,
                    'Argument "%s" of the method "%s" is nullable. Nullable arguments in test methods are forbidden.'
                );
            }
        }

        return $errors;
    }

    private function isTestMethod(Node\Stmt\ClassMethod $classMethod, ClassReflection $classReflection): bool
    {
        return $classMethod->isPublic()
            && TestClassDetector::isTestClass($classReflection->getName())
            && (Strings::startsWith($classMethod->name->name, self::TEST_METHOD_NAME_BEGINNING) || $this->hasTestDocComment($classMethod));
    }

    private function hasTestDocComment(Node\Stmt\ClassMethod $classMethod): bool
    {
        $docComment = $classMethod->getDocComment();

        return $docComment !== null
            && Strings::contains($docComment->getText(), self::TEST_ANNOTATION);
    }

    private function getErrorWithMessage(Node\Param $param, Node\Stmt\ClassMethod $classMethod, string $messageWithPlaceholders): string
    {
        /** @var Node\Expr\Variable $variable */
        $variable = $param->var;
        /** @var string $variableName */
        $variableName = $variable->name;

        return sprintf($messageWithPlaceholders, $variableName, $classMethod->name->name);
    }
}
