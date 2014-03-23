<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Token;
use th\l20n\Llk\Node\String;
use th\l20n\Llk\Node\Utils as NodeUtils;
use th\l20n\Llk\Node\Expression;

class Primary implements Node
{
    use NodeUtils;

    private $value;

    private static $idToClassName = [
        '#string'                => 'String',
        '#identifier_expression' => 'Expression\\Identifier',
        'token'                  => 'Token',
    ];

    public function __construct(TreeNode $ast)
    {
        $this->value = $this->build($ast->getChild(0));

        if ($this->value instanceof Token) {
            $this->value = (float) $this->value->value();

            if (((int) $this->value) == $this->value) {
                $this->value = (int) $this->value;
            }
        }
    }

    public function evaluate(EntityContext $context)
    {
        if ($this->value instanceof Node) {
            return $this->value->evaluate($context);
        }

        return $this->value;
    }
}
