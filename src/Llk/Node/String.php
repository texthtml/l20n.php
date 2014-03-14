<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node;
use th\l20n\Catalog;

class String implements Node
{
    private $parts;

    public function __construct(TreeNode $ast)
    {
        $this->parts = array_map(function ($stringPart) {
            if ($stringPart->isToken()) {
                return $stringPart->getValueValue();
            }

            return new Expander($stringPart);
        }, $ast->getChildren());
    }

    public function evaluate(Catalog $catalog, Array $data)
    {
        return array_reduce($this->parts, function ($prefix, $part) use ($catalog, $data) {
            if (is_string($part)) {
                return $prefix.$part;
            }

            return $prefix.$part->evaluate($catalog, $data);

        }, '');
    }
}
