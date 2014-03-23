<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Utils as NodeUtils;
use th\l20n\Llk\Node\Token;
use th\l20n\Llk\Node\Variable;

class Identifier implements Node
{
    use NodeUtils;

    private $type;
    private $identifier;

    private static $idToClassName = [
        'token'     => 'Token',
        '#variable' => 'Variable',
    ];

    public function __construct(TreeNode $ast)
    {
        $this->identifier = $this->build($ast->getChild(0));

        if ($this->identifier instanceof Token) {
            $this->type       = $this->identifier->name();
            $this->identifier = $this->identifier->value();
        }
    }

    public function evaluate(EntityContext $context)
    {
        if ($this->identifier instanceof Node) {
            return $this->identifier->evaluate($context);
        }

        if ($this->type !== 'this') {
            $value = $context->catalog()->entity($this->identifier);

            if ($value !== null) {
                $context->push($value);
            } else {
                $value = $context->catalog()->macro($this->identifier);
            }
        } else {
            $value = $context->this();
        }

        return $value->evaluate($context);
    }
}
