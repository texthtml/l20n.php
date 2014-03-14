<?php

namespace th\l20n\Llk;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Catalog;

interface Node
{
    public function evaluate(Catalog $catalog, Array $data);
}
