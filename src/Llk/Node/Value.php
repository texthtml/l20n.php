<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;

class Value implements Node
{
    use Utils;

    private $value;

    private static $idToClassName = [
        '#string' => 'String',
        '#hash'   => 'Hash'
    ];

    public function __construct(TreeNode $ast)
    {
        $this->value = $this->build($ast);
    }

    public function evaluate(EntityContext $context)
    {
        return $this->value->evaluate($context);
    }
}
