<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;

class Attributes implements Node
{
    private $attributes = [];

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        foreach ($children as $attributeAST) {
            $keyValuePair = new KeyValuePair($attributeAST);

            $this->attributes[$keyValuePair->identifier()] = $keyValuePair;
        }
    }

    public function evaluate(EntityContext $context)
    {
        return function ($attributeName) use ($context) {
            return $this->attributes[$attributeName]->evaluate($context);
        };
    }
}
