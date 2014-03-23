<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Error\IndexError;

class Variable implements Node
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

    public function identifier()
    {
        return $this->identifier;
    }

    public function evaluate(EntityContext $context)
    {
        $value = $context->variable($this->identifier);

        if ($value === null) {
            throw new IndexError("Reference to an unknown variable: {$this->identifier}.");
        }

        return $value;
    }
}
