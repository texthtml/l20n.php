<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Entity;
use th\l20n\Llk\Node\Error;
use th\l20n\Llk\Node\Error\ValueError;

class Expander implements Node
{
    private $expression;

    public function __construct(TreeNode $ast)
    {
        $this->expression = new Expression($ast->getChild(0));
    }

    public function evaluate(EntityContext $context)
    {
        if (!isset($context->bag->expanders)) {
            $context->bag->expanders = new \SplObjectStorage;
        }

        if ($context->bag->expanders->contains($this)) {
            throw new ValueError('Cyclic reference detected.');
        }

        $context->bag->expanders->attach($this);

        try {
            $value = $this->expression->evaluate($context);

            if (is_callable($value)) {
                if ($value instanceof Entity) {
                    $value = $value($context);
                } else {
                    $value = $value();
                }
            }
        } catch (Error $e) {
            $e->entity($context->this());

            throw new ValueError($e->getMessage(), 0, $e);
        }

        $context->bag->expanders->detach($this);
        $context->pop();

        return $value;
    }
}
