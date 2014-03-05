<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;

class Index
{
    use Utils;

    public function __construct(TreeNode $ast)
    {
        $this->expect($ast, 'index');
    }
}
