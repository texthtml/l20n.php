<?php

namespace th\l20n\Llk\Node\Expression\Part;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Expression;
use th\l20n\Llk\Node\Expression\Utils;
use th\l20n\Llk\Node\Error\ValueError;

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
            if (!is_array($macro) || count($macro) !== 2) {
                throw new ValueError('Expected a macro, got a non-callable.');
            }

            list($name, $closure) = $macro;

            if (!is_callable($closure)) {
                throw new ValueError('Expected a macro, got a non-callable.');
            }

            $closureReflection = new \ReflectionFunction($closure);
            $requiredParametersCount = $closureReflection->getNumberOfRequiredParameters();
            $parametersCount = count($parameters);
            if ($requiredParametersCount > $parametersCount) {
                throw new ValueError("$name() takes exactly $requiredParametersCount argument(s) ($parametersCount given)");
            }

            return call_user_func($closure, $parameters);
        };
    }
}
