<?php

namespace th\l20n\Llk\Node\Expression\Part;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Expression;
use th\l20n\Llk\Node\Expression\Utils;

class Call implements Node
{
    use Utils;

    private $parameters = [];

    public function __construct(TreeNode $ast)
    {
        foreach ($ast->getChildren() as $expressionAST) {
            $this->parameters[] = $this->build($expressionAST);
        }
    }

    public function evaluate(EntityContext $context)
    {
        $parameters = array_map(function ($parameterExpression) use ($context) {
            return $parameterExpression->evaluate($context);
        }, $this->parameters);

        return function ($macro) use ($parameters) {
            return call_user_func($macro, $parameters);
        };
    }
}
