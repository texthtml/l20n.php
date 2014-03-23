<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Token;

class Logical implements Node
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

        if ($this->operator === '||' && $left) {
            return true;
        }

        if ($this->operator === '&&' && !$left) {
            return false;
        }

        return (bool) $this->right->evaluate($context);
    }
}
