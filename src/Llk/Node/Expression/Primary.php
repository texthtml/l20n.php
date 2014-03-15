<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\String;
use th\l20n\Llk\Node\Utils;
use th\l20n\Llk\Node\Expression;

class Primary implements Node
{
    use Utils;

    private $value;

    private static $idToClassName = [
        '#string' => 'String',
        '#identifier_expression' => 'Expression\\Identifier'
    ];

    public function __construct(TreeNode $ast)
    {
        $this->value = $this->build($ast->getChild(0));
    }

    public function evaluate(EntityContext $context)
    {
        return $this->value->evaluate($context);
    }
}
