<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Token;
use th\l20n\Llk\Node\Error\IndexError;

class Binary implements Node
{
    use Utils;

    private $operator;
    private $left;
    private $right;

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        $leftAST = array_shift($children);
        $this->left = $this->build($leftAST);

        if (!empty($children)) {
            $operatorAST = array_shift($children);
            $operatorToken = new Token($operatorAST);

            $this->operator = $operatorToken->value();

            $rightAST = array_shift($children);
            $this->right = $this->build($rightAST);
        }
    }

    public function evaluate(EntityContext $context)
    {
        $left = $this->left->evaluate($context);

        if ($this->operator === null) {
            return $left;
        }

        $right = $this->right->evaluate($context);

        if (
            gettype($left) !== gettype($right) ||
            !in_array(gettype($left), ['string', 'integer', 'double'])
        ) {
            throw new IndexError("The {$this->operator} operator takes two numbers or two strings.");
        }

        if ($this->operator === '==') {
            return $left == $right;
        }

        if ($this->operator === '!=') {
            return $left != $right;
        }

        if (gettype($left) === 'string') {
            throw new IndexError("The {$this->operator} operator takes two numbers.");
        }

        if ($this->operator === '<') {
            return $left < $right;
        }

        if ($this->operator === '>') {
            return $left > $right;
        }

        if ($this->operator === '<=') {
            return $left <= $right;
        }

        if ($this->operator === '>=') {
            return $left >= $right;
        }

        if ($this->operator === '+') {
            return $left + $right;
        }

        if ($this->operator === '-') {
            return $left - $right;
        }

        if ($this->operator === '*') {
            return $left * $right;
        }

        if ($this->operator === '/') {
            return $left / $right;
        }

        if ($this->operator === '%') {
            return $left % $right;
        }
    }
}
