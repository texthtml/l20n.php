<?php

namespace spec\th\l20n\Llk\ParserSpec;

trait SimpleValues
{
    public function it_can_parse_a_simple_entity()
    {
        $l20n = <<<L20N
<brandName "Firefox">
L20N;
        $this->parse($l20n)->shouldMatchThisAST(
<<<AST
>  #l20n
>  >  #entity
>  >  >  token(identifier, brandName)
>  >  >  #string
>  >  >  >  token(double_string:string, Firefox)
AST
        );
    }

    public function it_can_parse_multiple_entities_at_once()
    {
        $l20n = <<<L20N
<brandName "Firefox">
<anotherBrandName "Thunderbird">
L20N;
        $this->parse($l20n)->shouldMatchThisAST(
<<<AST
>  #l20n
>  >  #entity
>  >  >  token(identifier, brandName)
>  >  >  #string
>  >  >  >  token(double_string:string, Firefox)
>  >  #entity
>  >  >  token(identifier, anotherBrandName)
>  >  >  #string
>  >  >  >  token(double_string:string, Thunderbird)
AST
        );
    }

    public function it_can_parse_a_simple_hashed_entity()
    {
        $l20n = <<<L20N
<brandName21 {
  masculine: "Firefox",
  feminine: "Aurora"
}>
L20N;
        $this->parse($l20n)->shouldMatchThisAST(
<<<AST
>  #l20n
>  >  #entity
>  >  >  token(identifier, brandName21)
>  >  >  #hash
>  >  >  >  #hashItem
>  >  >  >  >  token(identifier, masculine)
>  >  >  >  >  #string
>  >  >  >  >  >  token(double_string:string, Firefox)
>  >  >  >  #hashItem
>  >  >  >  >  token(identifier, feminine)
>  >  >  >  >  #string
>  >  >  >  >  >  token(double_string:string, Aurora)
AST
        );
    }

    public function it_can_parse_a_simple_hashed_entity_with_default()
    {
        $l20n = <<<L20N
<brandName22 {
  masculine: "Firefox",
  *feminine: "Aurora"
}>
L20N;
        $this->parse($l20n)->shouldMatchThisAST(
<<<AST
>  #l20n
>  >  #entity
>  >  >  token(identifier, brandName22)
>  >  >  #hash
>  >  >  >  #hashItem
>  >  >  >  >  token(identifier, masculine)
>  >  >  >  >  #string
>  >  >  >  >  >  token(double_string:string, Firefox)
>  >  >  >  #hashItem
>  >  >  >  >  token(times, *)
>  >  >  >  >  token(identifier, feminine)
>  >  >  >  >  #string
>  >  >  >  >  >  token(double_string:string, Aurora)
AST
        );
    }

    public function it_can_parse_a_simple_hashed_entity_with_attributes()
    {
        $l20n = <<<L20N
<brandName23['feminine'] {
  masculine: "Firefox",
  feminine: "Aurora"
}>

<brandName24['neutral'] {
  masculine: "Firefox",
  feminine: "Aurora"
}>

<brandName25['feminine', 'foo'] {
  masculine: "Firefox",
  feminine: "Aurora"
}>
L20N;
        $this->parse($l20n)->shouldMatchThisAST(
<<<AST
>  #l20n
>  >  #entity
>  >  >  token(identifier, brandName23)
>  >  >  #index
>  >  >  >  #expression
>  >  >  >  >  #primary_expression
>  >  >  >  >  >  #string
>  >  >  >  >  >  >  token(single_string:string, feminine)
>  >  >  #hash
>  >  >  >  #hashItem
>  >  >  >  >  token(identifier, masculine)
>  >  >  >  >  #string
>  >  >  >  >  >  token(double_string:string, Firefox)
>  >  >  >  #hashItem
>  >  >  >  >  token(identifier, feminine)
>  >  >  >  >  #string
>  >  >  >  >  >  token(double_string:string, Aurora)
>  >  #entity
>  >  >  token(identifier, brandName24)
>  >  >  #index
>  >  >  >  #expression
>  >  >  >  >  #primary_expression
>  >  >  >  >  >  #string
>  >  >  >  >  >  >  token(single_string:string, neutral)
>  >  >  #hash
>  >  >  >  #hashItem
>  >  >  >  >  token(identifier, masculine)
>  >  >  >  >  #string
>  >  >  >  >  >  token(double_string:string, Firefox)
>  >  >  >  #hashItem
>  >  >  >  >  token(identifier, feminine)
>  >  >  >  >  #string
>  >  >  >  >  >  token(double_string:string, Aurora)
>  >  #entity
>  >  >  token(identifier, brandName25)
>  >  >  #index
>  >  >  >  #expression
>  >  >  >  >  #primary_expression
>  >  >  >  >  >  #string
>  >  >  >  >  >  >  token(single_string:string, feminine)
>  >  >  >  #expression
>  >  >  >  >  #primary_expression
>  >  >  >  >  >  #string
>  >  >  >  >  >  >  token(single_string:string, foo)
>  >  >  #hash
>  >  >  >  #hashItem
>  >  >  >  >  token(identifier, masculine)
>  >  >  >  >  #string
>  >  >  >  >  >  token(double_string:string, Firefox)
>  >  >  >  #hashItem
>  >  >  >  >  token(identifier, feminine)
>  >  >  >  >  #string
>  >  >  >  >  >  token(double_string:string, Aurora)
AST
        );
    }
}
