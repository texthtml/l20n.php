<?php

namespace spec\th\l20n\Llk\ParserSpec;

trait Macros
{
    public function it_can_parse_a_macro()
    {
        $l20n = <<<'L20N'
<plural($n) { $n == 1 ? 'one' : 'many' }>
L20N;
        $this->parse($l20n)->shouldMatchThisAST(
<<<AST
>  #l20n
>  >  #macro
>  >  >  token(identifier, plural)
>  >  >  #variable
>  >  >  >  token(identifier, n)
>  >  >  #expression
>  >  >  >  #conditional_expression
>  >  >  >  >  #binary_expression
>  >  >  >  >  >  #primary_expression
>  >  >  >  >  >  >  #identifier_expression
>  >  >  >  >  >  >  >  #variable
>  >  >  >  >  >  >  >  >  token(identifier, n)
>  >  >  >  >  >  token(equal, ==)
>  >  >  >  >  >  #primary_expression
>  >  >  >  >  >  >  token(literal, 1)
>  >  >  >  >  #expression
>  >  >  >  >  >  #primary_expression
>  >  >  >  >  >  >  #string
>  >  >  >  >  >  >  >  token(single_string:string, one)
>  >  >  >  >  #expression
>  >  >  >  >  >  #primary_expression
>  >  >  >  >  >  >  #string
>  >  >  >  >  >  >  >  token(single_string:string, many)
AST
        );
    }
    public function it_can_parse_a_macro_call()
    {
        $l20n = <<<'L20N'
<unreadPlural[plural($unreadNotifications)] {
  one: "One unread notification",
  many: "{{ $unreadNotifications }} unread notifications"
}>
L20N;
        $this->parse($l20n)->shouldMatchThisAST(
<<<AST
>  #l20n
>  >  #entity
>  >  >  token(identifier, unreadPlural)
>  >  >  #index
>  >  >  >  #expression
>  >  >  >  >  #member_expression
>  >  >  >  >  >  #primary_expression
>  >  >  >  >  >  >  #identifier_expression
>  >  >  >  >  >  >  >  token(identifier, plural)
>  >  >  >  >  >  #call_expression
>  >  >  >  >  >  >  #expression
>  >  >  >  >  >  >  >  #primary_expression
>  >  >  >  >  >  >  >  >  #identifier_expression
>  >  >  >  >  >  >  >  >  >  #variable
>  >  >  >  >  >  >  >  >  >  >  token(identifier, unreadNotifications)
>  >  >  #hash
>  >  >  >  #hashItem
>  >  >  >  >  token(identifier, one)
>  >  >  >  >  #string
>  >  >  >  >  >  token(double_string:string, One unread notification)
>  >  >  >  #hashItem
>  >  >  >  >  token(identifier, many)
>  >  >  >  >  #string
>  >  >  >  >  >  #expander
>  >  >  >  >  >  >  #expression
>  >  >  >  >  >  >  >  #primary_expression
>  >  >  >  >  >  >  >  >  #identifier_expression
>  >  >  >  >  >  >  >  >  >  #variable
>  >  >  >  >  >  >  >  >  >  >  token(identifier, unreadNotifications)
>  >  >  >  >  >  token(double_string:string,  unread notifications)
AST
        );
    }
}
