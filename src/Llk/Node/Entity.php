<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node;
use th\l20n\Catalog;

class Entity implements Node
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

    public function evaluate(Catalog $catalog, Array $data)
    {
        $value = $this->value->evaluate($catalog, $data);

        if (!is_callable($value)) {
            return $value;
        }

        $value = $value($this->indexes);

        return $value();
    }

    public function __invoke(Catalog $catalog, Array $data)
    {
        return $this->evaluate($catalog, $data);
    }

    public function getAttributes()
    {
        return $this->attributes;
    }
}
