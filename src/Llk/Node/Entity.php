<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;

class Entity
{
    use Utils;

    private $identifier;
    private $index;
    private $value;
    private $attributes;

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        $identifierAst    = array_shift($children);
        $identifierToken  = new Token($identifierAst);
        $this->identifier = $identifierToken->value();

        $indexAST = array_shift($children);
        try {
            $this->index = new Index($indexAST);
            $valueAST = array_shift($children);
        } catch (\Exception $e) {
            $valueAST = $indexAST;
        }

        $this->value = new Value($valueAST);

        $attributesAST = array_shift($children);
        if ($attributesAST !== null) {
            $this->attributes = new Attributes($attributesAST);
        }
    }

    public function identifier()
    {
        return $this->identifier;
    }

    public function __invoke(Array $data = [])
    {
        return $this->value->get($data, $this->index);
    }
}
