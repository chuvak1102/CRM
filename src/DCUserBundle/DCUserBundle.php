<?php

namespace DCUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DCUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
