<?php

namespace th\l20n;

interface Entity
{
    public function __invoke(EntityContext $context);
}
