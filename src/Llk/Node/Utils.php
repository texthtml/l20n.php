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
            $className = get_class();
            throw new \Exception("$className: Error, unexpected '$id', expecting '$expectedIds'");
        }

        return $id;
    }

    private function build(TreeNode $ast)
    {
        $this->expect($ast, array_keys(static::$idToClassName));

        $className = '\\th\\l20n\\Llk\\Node\\'.static::$idToClassName[$ast->getId()];
        return new $className($ast);
    }
}
