<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;

class Index implements Node
{
    use Utils;

    private $expressions;

    public function __construct(TreeNode $ast)
    {
        $this->expressions = array_map(function ($expressionAST) {
            return new Expression($expressionAST);
        }, $ast->getChildren());
    }

    public function expressions()
    {
        return $this->expressions;
    }

    public function evaluate(EntityContext $context)
    {
        notimplemented();
    }
}
