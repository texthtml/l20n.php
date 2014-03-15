<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;

class Member implements Node
{
    private $base;
    private $parts = [];

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        $parenthesisAST = array_shift($children);
        $this->base = new Primary($parenthesisAST);

        while (($partAST = array_shift($children)) !== null) {
            $this->parts[] = new MemberPart($partAST);
        }
    }

    public function evaluate(EntityContext $context)
    {
        $base = $this->base->evaluate($context);

        return array_reduce($this->parts, function ($base, $part) use ($context) {
            $value = $part->evaluate($context);

            return $value($base);
        }, $base);
    }
}
