<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node\Token;
use th\l20n\Llk\Node;
use th\l20n\Catalog;

class Logical implements Node
{
    private $operator;
    private $left;
    private $right;

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        $leftAST = array_shift($children);
        $this->left = new Binary($leftAST);

        if (!empty($children)) {
            $operatorAST = array_shift($children);
            $operatorToken = new Token($operatorAST);

            $this->operator = $operatorToken->value();

            $rightAST = array_shift($children);
            $this->right = new Logical($rightAST);
        }
    }

    public function evaluate(Catalog $catalog, Array $data)
    {
        $left = $this->left->evaluate($catalog, $data);
        if ($this->operator === null) {
            return $left;
        }

        if ($this->operator === '||' && $left) {
            return true;
        }

        if ($this->operator === '&&' && !$left) {
            return false;
        }

        return (bool) $this->right->evaluate($catalog, $data);
    }
}
