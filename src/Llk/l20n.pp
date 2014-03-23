%skip space           \s

%skip comment_ \/\*.*\*\/

%token _expander \}\} -> __shift__

%token default \*
%token comma   ,
%token colon   :
%token dot     \.

%token question_mark \?
%token or            \|\|
%token and           \&\&
%token equal         ==
%token not_equal     \!=
%token less_or_equal \<=
%token more_or_equal \>=
%token plus          \+
%token minus         \-
%token times         \*
%token fraction      \/
%token mod           \%
%token not           \!

%token angle_               <
%token _angle               >
%token bracket_             \[
%token _bracket             ]
%token parenthesis_         \(
%token _parenthesis         \)
%token brace_               \{
%token _brace               \}

%token this     \~
%token variable \$
%token global   \@

%token literal \-?(0|[1-9]\d*)(\.\d+)?([eE][\+\-]?\d+)?

%token singlequote_                '                           -> single_string
%token single_string:_singlequote  '                           -> __shift__
%token single_string:expander_     \{\{                        -> default
%token single_string:escaped       \\(['"\\]|\{\{)
%token single_string:string        ([^'\\\{]|\{[^'\\\{])+

%token doublequote_                "                           -> double_string
%token double_string:_doublequote  "                           -> __shift__
%token double_string:expander_     \{\{                        -> default
%token double_string:escaped       \\(['"\\]|\{\{)
%token double_string:string        ([^"\\\{]|\{[^"\\\{])+

%token tripledoublequote_                      """       -> tripledouble_string
%token tripledouble_string:_tripledoublequote  """       -> __shift__
%token tripledouble_string:expander_           \{\{      -> default
%token tripledouble_string:escaped             \\(['"\\]|\{\{)
%token tripledouble_string:string              [^"\\]+

%token triplesinglequote_                      '         -> triplesingle_string
%token triplesingle_string:_triplesinglequote  '         -> __shift__
%token triplesingle_string:expander_           \{\{      -> default
%token triplesingle_string:escaped             \\(['"\\]|\{\{)
%token triplesingle_string:string              [^'\\]+

%token identifier      [_a-zA-Z]\w*



#l20n:
    entry()*

entry:
    entity() | macro()

#macro:
    ::angle_:: <identifier> ::parenthesis_:: variable() (::comma:: variable() )* ::_parenthesis:: ::brace_:: expression() ::_brace:: ::_angle::

#entity:
    ::angle_:: <identifier> index()? value() attributes()* ::_angle::

#index:
    ::bracket_:: expression() ( ::comma:: expression() )* ::comma::? ::_bracket::

value:
    string() | hash()

quote_:
    ::singlequote_:: | ::doublequote_:: | ::triplesinglequote_:: | ::tripledoublequote_::

_quote:
    ::_singlequote:: | ::_doublequote:: | ::_triplesinglequote:: | ::_tripledoublequote::

#string:
    quote_()
    ( <escaped> | expander() | <string> )*
    _quote()

#expander:
    ::expander_:: expression() ::_expander::

#hash:
    ::brace_:: hashItem() ( ::comma:: hashItem() )* ::comma::? ::_brace::

#hashItem:
    <default>? <identifier> ::colon:: value()

#attributes:
    keyValuePair()+

#keyValuePair:
    <identifier> index()? ::colon:: value()

#expression:
    conditional_expression()

conditional_expression:
    logical_expression() ( ::question_mark:: expression() ::colon:: expression() #conditional_expression )?

logical_expression:
    binary_expression() ( ( <or> | <and> ) logical_expression() #logical_expression )?

binary_expression:
    unary_expression() ( (<equal> | <not_equal> | <angle_> | <_angle> | <less_or_equal> | <more_or_equal> | <plus> | <minus> | <times> | <fraction> | <mod> ) binary_expression() #binary_expression )?

unary_expression:
    ( ( <plus> | <minus> | <not> ) unary_expression() #unary_expression ) | member_expression()

member_expression:
    parenthesis_expression() ( member_expression_part() #member_expression )*

member_expression_part:
    call_expression() | property_expression() | attr_expression()

#call_expression:
    ::parenthesis_:: expression() ( ::comma:: expression() )* ::_parenthesis::

#property_expression:
    ::bracket_:: expression() ::_bracket:: | ::dot:: <identifier>

#attr_expression:
    ::colon:: ::colon:: ::bracket_:: expression() ::_bracket:: | ::colon:: ::colon:: <identifier>

parenthesis_expression:
    ::parenthesis_:: expression() ::_parenthesis:: | primary_expression()

#primary_expression:
    <literal> | value() | identifier_expression()

#identifier_expression:
    <identifier> | variable() | globals_expression() | <this>

#variable:
    ::variable:: <identifier>

#globals_expression:
    ::global:: <identifier>
