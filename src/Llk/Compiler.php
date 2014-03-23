<?php

namespace th\l20n\Llk;

use th\l20n\Compiler as l20nCompiler;
use th\l20n\Llk\Node\Entity;
use th\l20n\Llk\Node\Macro;
use th\l20n\Llk\Node\Utils;
use Hoa\Compiler\Llk\TreeNode;

class Compiler implements l20nCompiler
{
    use Utils;

    private $expression;

    private static $idToClassName = [
        '#entity' => 'Entity',
        '#macro'  => 'Macro',
    ];

    private $entities = [];
    private $macros = [];

    public function compile($ast)
    {
        return $this->compileNode($ast);
    }

    private function compileNode(TreeNode $ast)
    {
        $id = $ast->getId();

        if ($id !== '#l20n') {
            throw new \Exception("Error, unexpected '$id', expecting '#l20n'", 1);
        }

        foreach ($ast->getChildren() as $entryAST) {
            $entry = $this->build($entryAST);

            if ($entry instanceof Entity) {
                $this->entities[$entry->identifier()] = $entry;
            }

            if ($entry instanceof Macro) {
                $this->macros[$entry->identifier()] = $entry;
            }
        }

        return [];
    }

    public function entity($identifier)
    {
        if (array_key_exists($identifier, $this->entities)) {
            return $this->entities[$identifier];
        }
    }

    public function macro($identifier)
    {
        if (array_key_exists($identifier, $this->macros)) {
            return $this->macros[$identifier];
        }
    }
}
