<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;

class KeyValuePair implements Node
{
    use Utils;

    private $identifier;
    private $indexes;
    private $value;

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        $identifierAST    = array_shift($children);
        $identifierToken  = new Token($identifierAST);
        $this->identifier = $identifierToken->value();

        $indexAST = array_shift($children);
        if ($indexAST->getId() === '#index') {
            $index = new Index($indexAST);
            $this->indexes = $index->expressions();
            $valueAST = array_shift($children);
        } else {
            $valueAST = $indexAST;
        }

        $this->value = new Value($valueAST);
    }

    public function identifier()
    {
        return $this->identifier;
    }

    public function evaluate(EntityContext $context)
    {
        $value = $this->value->evaluate($context);

        if (!is_callable($value)) {
            return $value;
        }

        return $value($this->indexes);
    }
}
