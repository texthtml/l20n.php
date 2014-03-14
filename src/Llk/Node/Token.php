<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node;

class Token
{
    use Utils;

    private $name;
    private $value;

    public function __construct(TreeNode $ast)
    {
        $this->expect($ast, 'token');

        $this->name  = $ast->getValueToken();
        $this->value = $ast->getValueValue();
    }

    public function name()
    {
        return $this->name;
    }

    public function value()
    {
        return $this->value;
    }
}
