<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;

class Value
{
    use Utils;

    private $string;

    public function __construct(TreeNode $ast)
    {
        if ($this->expect($ast, ['#string', 'hash']) === '#string') {
            $this->string = new String($ast);
        } else {
            throw new \Exception("Error Processing Request", 1);

        }
    }

    public function get(Array $data = [])
    {
        if ($this->string) {
            return $this->string->get($data);
        } else {
            throw new \Exception("Error Processing Request", 1);

        }
    }
}
