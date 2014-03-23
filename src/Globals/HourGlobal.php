<?php

namespace th\l20n\Globals;

class HourGlobal
{
    public function __invoke()
    {
        return (int) date('H');
    }
}
