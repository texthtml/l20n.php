<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;

trait Utils
{
    private function expect(TreeNode $ast, $ids)
    {
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $id = $ast->getId();
        if (!in_array($id, $ids)) {
            $expectedIds = implode("', '", $ids);
            throw new \Exception("Error, unexpected '$id', expecting '$expectedIds'");
        }

        return $id;
    }
}
