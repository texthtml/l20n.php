<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Token;

class Binary implements Node
{
    private $operator;
    private $left;
    private $right;

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        $leftAST = array_shift($children);
        $this->left = new Unary($leftAST);

        if (!empty($children)) {
            $operatorAST = array_shift($children);
            $operatorToken = new Token($operatorAST);

            $this->operator = $operatorToken->value();

            $rightAST = array_shift($children);
            $this->right = new Binary($rightAST);
        }
    }

    public function evaluate(EntityContext $context)
    {
        $left = $this->left->evaluate($context);

        if ($this->operator === null) {
            return $left;
        }

        $right = $this->right->evaluate($context);

        if ($this->operator === '==') {
            return $left == $right;
        }

        if ($this->operator === '!=') {
            return $left != $right;
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
