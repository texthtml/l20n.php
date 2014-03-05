<?php

namespace th\l20n\Llk;

use th\l20n\Compiler as l20nCompiler;
use th\l20n\Llk\Node\Entity;
use Hoa\Compiler\Llk\TreeNode;

class Compiler implements l20nCompiler
{
    private $entities = [];

    public function compile($ast)
    {
        return $this->compileNode($ast);
    }

    private function compileNode(TreeNode $ast)
    {
        $id = $ast->getId();

        if ($id !== '#lol') {
            throw new \Exception("Error, unexpected '$id', expecting '#lol'", 1);
        }

        foreach ($ast->getChildren() as $entry) {
            $id = $entry->getId();

            if ($id === '#entity') {
                $entity = new Entity($entry);
                $this->entities[$entity->identifier()] = $entity;
            } else {
                throw new \Exception("Error, unexpected '$id', expecting '#entity'", 1);
            }
        }

        return [];
    }

    public function getEntity($identifier)
    {
        if (array_key_exists($identifier, $this->entities)) {
            return $this->entities[$identifier];
        }
    }
}
