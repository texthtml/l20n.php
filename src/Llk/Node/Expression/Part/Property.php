<?php

namespace th\l20n\Llk\Node\Expression\Part;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Utils;
use th\l20n\Llk\Node\Token;
use th\l20n\Llk\Node\Value;

class Property implements Node
{
    use Utils;

    private $attributeName;

    private static $idToClassName = [
        'token' => 'Token'
    ];

    public function __construct(TreeNode $ast)
    {
        $this->attributeName = $this->build($ast->getChild(0))->value();
    }

    public function evaluate(EntityContext $context)
    {
        return function ($hash) use ($context) {
            return $hash([$this->attributeName]);
        };
    }
}
