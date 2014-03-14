<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node\Expression;
use th\l20n\Llk\Node;
use th\l20n\Catalog;

class Conditional implements Node
{
    private $condition;
    private $left;
    private $right;

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        $logicalAST = array_shift($children);
        $this->condition = new Logical($logicalAST);

        if (!empty($children)) {
            $leftAST = array_shift($children);
            $this->left = new Expression($leftAST);

            $rightAST = array_shift($children);
            $this->right = new Expression($rightAST);
        }
    }

    public function evaluate(Catalog $catalog, Array $data)
    {
        $condition = $this->condition->evaluate($catalog, $data);

        if ($this->left === null) {
            return $condition;
        }

        if ($condition) {
            return $this->left->evaluate($catalog, $data);
        }

        return $this->right->evaluate($catalog, $data);
    }
}