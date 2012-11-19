<?php

namespace App\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\CommonBundle\Classes\SwiftmailerConfigure;

class AppUserBundle extends Bundle
{
    public function getParent()
    {
        new SwiftmailerConfigure();
        return 'FOSUserBundle';
    }
}
