<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node;
use th\l20n\Catalog;

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

    public function evaluate(Catalog $catalog, Array $data)
    {
        $base = $this->base->evaluate($catalog, $data);

        return array_reduce($this->parts, function ($base, $part) use ($catalog, $data) {
            $value = $part->evaluate($catalog, $data);

            return $value($base);
        }, $base);
    }
}
