<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Error\IndexError;

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

    public function evaluate(EntityContext $context)
    {
        return function ($index = null) use ($context) {
            if ($index === null) {
                $value = $this->default;
            } else {
                if ($index instanceof Node) {
                    $index = $index->evaluate($context);
                }

                if (!is_string($index)) {
                    throw new IndexError('Hash key lookup failed.');
                } elseif (!array_key_exists($index, $this->items)) {
                    throw new IndexError('Hash key lookup failed (tried "'.$index.'").');
                }

                $value = $this->items[$index];
            }

            if ($value === null) {
                throw new IndexError('Hash key lookup failed.');
            }

            return $value->evaluate($context);
        };
    }
}
