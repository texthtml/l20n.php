<?php

namespace th\l20n\Llk\Node\Error;

use th\l20n\Llk\Node\Error;

class ValueError extends \Exception implements Error
{
    use Utils;
}
