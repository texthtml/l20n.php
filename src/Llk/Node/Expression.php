<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node\Utils;
use th\l20n\Llk\Node\Expression\Conditional;
use th\l20n\Llk\Node;
use th\l20n\Catalog;

class Expression implements Node
{
    use Utils;

    private $expression;

    private static $idToClassName = [
        '#primary_expression' => 'Expression\\Primary',
        '#member_expression' => 'Expression\\Member'
    ];

    public function __construct(TreeNode $ast)
    {
        $this->expression = $this->build($ast->getChild(0));
    }

    public function evaluate(Catalog $catalog, Array $data)
    {
        return $this->expression->evaluate($catalog, $data);
    }
}
