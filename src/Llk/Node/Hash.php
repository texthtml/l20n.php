<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node;
use th\l20n\Catalog;
use th\l20n\Llk\Node\Exception\IndexError;

class Hash implements Node
{
    private $items = [];
    private $default;

    public function __construct(TreeNode $ast)
    {
        foreach ($ast->getChildren() as $hashItemAST) {
            $item = new HashItem($hashItemAST);
            $this->items[$item->identifier()] = $item;

            if ($item->isDefault()) {
                $this->default = $item;
            }
        }
    }

    public function evaluate(Catalog $catalog, Array $data)
    {
        return function (Array $defaultIndexes = null) use ($catalog, $data) {
            return function (Array $requestedIndexes = null) use ($catalog, $data, $defaultIndexes) {
                $indexes = $defaultIndexes?:[];
                if ($requestedIndexes !== null) {
                    $indexes = $requestedIndexes;
                }
                $index = array_shift($indexes);

                if ($index === null) {
                    $value = $this->default;
                } else {
                    if ($index instanceof Node) {
                        $index = $index->evaluate($catalog, $data);
                    }

                    if (!array_key_exists($index, $this->items)) {
                        throw new IndexError('Hash key lookup failed (tried "'.$index.'").');
                    }
                    $value = $this->items[$index];
                }

                if ($value === null) {
                    throw new IndexError('Hash key lookup failed.');
                }
                return $value->evaluate($catalog, $data);
            };
        };
    }
}
