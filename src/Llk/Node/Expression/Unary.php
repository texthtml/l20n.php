<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node\Token;
use th\l20n\Llk\Node;
use th\l20n\Catalog;

class Unary implements Node
{
    private $operator;
    private $member;

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        $operatorAST = array_shift($children);
        if ($operatorAST->isToken()) {
            $operatorToken = new Token($operatorAST);

            $memberAST = array_shift($children);
        } else {
            $memberAST = $operatorAST;
        }

        $this->member = new Member($memberAST);
    }

    public function evaluate(Catalog $catalog, Array $data)
    {
        $member = $this->member->evaluate($catalog, $data);

        if ($this->operator === null) {
            return $member;
        }

        if ($this->operator === '+') {
            return + $member;
        }

        if ($this->operator === '-') {
            return - $member;
        }

        if ($this->operator === '!') {
            return ! $member;
        }
    }
}
