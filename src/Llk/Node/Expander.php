<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Entity;
use th\l20n\Catalog;

class Expander implements Node
{
    private $expression;

    public function __construct(TreeNode $ast)
    {
        $this->expression = new Expression($ast->getChild(0));
    }

    public function evaluate(Catalog $catalog, Array $data)
    {
        $value = $this->expression->evaluate($catalog, $data);

        if (!is_callable($value)) {
            return $value;
        }

        if ($value instanceof Entity) {
            return $value($catalog, $data);
        }

        return $value();
    }
}
