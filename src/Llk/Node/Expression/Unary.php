<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Token;

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

            $this->operator = $operatorToken->value();

            $memberAST = array_shift($children);
        } else {
            $memberAST = $operatorAST;
        }

        $this->member = new Member($memberAST);
    }

    public function evaluate(EntityContext $context)
    {
        $member = $this->member->evaluate($context);

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
