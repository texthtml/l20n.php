<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node;
use th\l20n\Catalog;

class HashItem implements Node
{
    use Utils;

    private $default = false;
    private $identifier;
    private $value;

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        $defaultAst = array_shift($children);
        $defaultToken = new Token($defaultAst);

        if ($defaultToken->name() === 'default') {
            $this->default = true;

            $identifierAST = array_shift($children);
            $identifierToken = new Token($identifierAST);
        } else {
            $identifierToken = $defaultToken;
        }

        $this->identifier = $identifierToken->value();

        $valueAST = array_shift($children);
        $this->value = new Value($valueAST);
    }

    public function isDefault()
    {
        return $this->default;
    }

    public function identifier()
    {
        return $this->identifier;
    }

    public function evaluate(Catalog $catalog, Array $data)
    {
        return $this->value->evaluate($catalog, $data);
    }
}
