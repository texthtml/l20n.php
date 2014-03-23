<?php

namespace th\l20n\Llk\Node\Expression\Part;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\EntityContext;
use th\l20n\Llk\Node;
use th\l20n\Llk\Node\Utils;
use th\l20n\Llk\Node\Token;
use th\l20n\Llk\Node\Value;
use th\l20n\Llk\Node\Entity;
use th\l20n\Llk\Node\Expression;
use th\l20n\Llk\Node\Error\ValueError;

class Property implements Node
{
    use Utils;

    private $propertyName;

    private static $idToClassName = [
        '#expression' => 'Expression',
        'token'       => 'Token',
    ];

    public function __construct(TreeNode $ast)
    {
        $this->propertyName = $this->build($ast->getChild(0));

        if ($this->propertyName instanceof Token) {
            $this->propertyName = $this->propertyName->value();
        }
    }

    public function evaluate(EntityContext $context)
    {
        return function ($obj) use ($context) {
            $propertyName = $this->propertyName;

            if ($propertyName instanceof Expression) {
                $propertyName = $this->propertyName->evaluate($context);
            }

            if (is_callable($obj)) {
                return $obj($propertyName);
            }

            if (is_array($obj)) {
                return $obj[$propertyName];
            }

            if (is_object($obj)) {
                return $obj->$propertyName;
            }

            $type = gettype($obj);
            throw new ValueError("Cannot get property of a $type: $propertyName.");
        };
    }
}
