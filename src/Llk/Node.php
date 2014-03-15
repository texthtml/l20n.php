<?php

namespace th\l20n\Llk;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;

interface Node
{
    public function evaluate(EntityContext $context);
}
