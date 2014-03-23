<?php

namespace th\l20n\Llk\Node\Expression;

use Hoa\Compiler\Llk\TreeNode;
use th\l20n\Llk\Node\Utils as NodeUtils;

trait Utils
{
    use NodeUtils;

    private static $idToClassName = [
        '#expression'             => 'Expression',
        '#conditional_expression' => 'Expression\\Conditional',
        '#logical_expression'     => 'Expression\\Logical',
        '#binary_expression'      => 'Expression\\Binary',
        '#unary_expression'       => 'Expression\\Unary',
        '#member_expression'      => 'Expression\\Member',
        '#property_expression'    => 'Expression\\Part\\Property',
        '#attr_expression'        => 'Expression\\Part\\Attr',
        '#call_expression'        => 'Expression\\Part\\Call',
        '#primary_expression'     => 'Expression\\Primary',
        '#identifier_expression'  => 'Expression\\Identifier',
    ];
}
