<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node\Utils;
use th\l20n\Llk\Node\Token;
use th\l20n\Llk\Node;
use th\l20n\Catalog;

class Identifier implements Node
{
    use Utils;

    private $identifier;

    private static $idToClassName = [
        'token' => 'Token'
    ];

    public function __construct(TreeNode $ast)
    {
        $this->identifier = $this->build($ast->getChild(0))->value();
    }

    public function evaluate(Catalog $catalog, Array $data)
    {
        return $catalog->getEntity($this->identifier);
    }
}
