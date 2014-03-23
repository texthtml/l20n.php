<?php

namespace th\l20n\Llk\Node;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Entity as L20NEntity;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;

class Macro implements Node
{
    private $identifier;
    private $variables = [];
    private $expression;

    public function __construct(TreeNode $ast)
    {
        $children = $ast->getChildren();

        $identifierAST    = array_shift($children);
        $identifierToken  = new Token($identifierAST);
        $this->identifier = $identifierToken->value();

        while (count($children) > 1) {
            $variableAST = array_shift($children);
            $this->variables[] = new Variable($variableAST);
        }

        $expressionAST = array_shift($children);

        $this->expression = new Expression($expressionAST);
    }

    public function identifier()
    {
        return $this->identifier;
    }

    public function evaluate(EntityContext $context)
    {
        return function (Array $parameters) use ($context) {
            $data = [];
            foreach ($this->variables as $n => $variable) {
                $data[$variable->identifier()] = $parameters[$n];
            }

            $macroContext = new EntityContext($context->catalog(), $context->this(), $data, $context->globalsExpressions());

            return $this->expression->evaluate($macroContext);
        };
    }
}
