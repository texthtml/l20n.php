<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Expression;

class Conditional implements Node
{
    use Utils;

    private $condition;
    private $left;
    private $right;

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        $logicalAST = array_shift($children);
        $this->condition = $this->build($logicalAST);

        if (!empty($children)) {
            $leftAST = array_shift($children);
            $this->left = $this->build($leftAST);

            $rightAST = array_shift($children);
            $this->right = $this->build($rightAST);
        }
    }

    public function evaluate(EntityContext $context)
    {
        $condition = $this->condition->evaluate($context);

        if ($this->left === null) {
            return $condition;
        }

        if ($condition) {
            return $this->left->evaluate($context);
        }

        return $this->right->evaluate($context);
    }
}
