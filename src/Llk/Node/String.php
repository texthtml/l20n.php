<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;

class String
{
    use Utils;

    private $parts;

    public function __construct(TreeNode $ast)
    {
        $this->parts = array_map(function ($stringPart) {
            if ($stringPart->isToken()) {
                return $stringPart->getValueValue();
            }

            return $stringPart;
        }, $ast->getChildren());
    }

    public function get(Array $data = [])
    {
        return array_reduce($this->parts, function ($prefix, $part) use ($data) {
            if (is_string($part)) {
                return $prefix.$part;
            }

            throw new \Exception("Error Processing Request", 1);

        }, '');
    }
}
