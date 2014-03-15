<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Utils;
use th\l20n\Llk\Node\Token;

class Identifier implements Node
{
    use Utils;

    private $type;
    private $identifier;

    private static $idToClassName = [
        'token' => 'Token'
    ];

    public function __construct(TreeNode $ast)
    {
        $token = $this->build($ast->getChild(0));

        $this->type       = $token->name();
        $this->identifier = $token->value();
    }

    public function evaluate(EntityContext $context)
    {
        if ($this->type !== 'this') {
            $context->push($context->catalog()->entity($this->identifier));
        }

        return $context->this();
    }
}
