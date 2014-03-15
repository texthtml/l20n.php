<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Entity as L20NEntity;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;

class Entity implements Node, L20NEntity
{
    private $identifier;
    private $indexes = [];
    private $value;
    private $attributes;

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        $identifierAST    = array_shift($children);
        $identifierToken  = new Token($identifierAST);
        $this->identifier = $identifierToken->value();

        $indexAST = array_shift($children);
        if ($indexAST->getId() === '#index') {
            $index = new Index($indexAST);
            $this->indexes = $index->expressions();

            $valueAST = array_shift($children);
        } else {
            $valueAST = $indexAST;
        }

        $this->value = new Value($valueAST);

        $attributesAST = array_shift($children);
        if ($attributesAST !== null) {
            $this->attributes = new Attributes($attributesAST);
        }
    }

    public function identifier()
    {
        return $this->identifier;
    }

    public function evaluate(EntityContext $context)
    {
        try {
            $value = $this->value->evaluate($context);

            if (!is_callable($value)) {
                return $value;
            }

            $value = $value($this->indexes);

            return $value();
        } catch (Error $e) {
            $e->entity($this);

            throw $e;
        }
    }

    public function __invoke(EntityContext $context)
    {
        return $this->evaluate($context);
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
