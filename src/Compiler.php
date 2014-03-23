<?php

namespace th\l20n;

interface Compiler
{
    public function compile($ast);

    public function entity($id);

    public function macro($id);
}
