<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node;
use th\l20n\Catalog;

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

    public function evaluate(Catalog $catalog, Array $data)
    {
        return function ($attributeName) use ($catalog, $data) {
            return $this->attributes[$attributeName]->evaluate($catalog, $data);
        };
    }
}
