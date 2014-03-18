<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Error\ValueError;

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

    public function evaluate(EntityContext $context)
    {
        return array_reduce($this->parts, function ($prefix, $part) use ($context) {
            if (is_string($part)) {
                return $prefix.$part;
            }

            try {
                $suffix = $part->evaluate($context);

                return $prefix.$suffix;
            } catch (\Exception $e) {
                throw new ValueError($e->getMessage(), 0, $e);
            }

        }, '');
    }
}
