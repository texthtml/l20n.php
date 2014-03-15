<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Utils;
use th\l20n\Llk\Node\Token;
use th\l20n\Llk\Node\Value;

class MemberPart implements Node
{
    use Utils;

    private $memberPart;

    private static $idToClassName = [
        '#attr_expression'     => 'Expression\\Part\\Attr',
        '#property_expression' => 'Expression\\Part\\Property',
    ];

    public function __construct(TreeNode $ast)
    {
        $this->memberPart = $this->build($ast);
    }

    public function evaluate(EntityContext $context)
    {
        return $this->memberPart->evaluate($context);
    }
}
