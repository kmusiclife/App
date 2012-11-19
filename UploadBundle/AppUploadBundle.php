<?php

namespace App\UploadBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\CommonBundle\Classes\SwiftmailerConfigure;

class AppUploadBundle extends Bundle
{
    public function getParent()
    {
        new SwiftmailerConfigure();
    }
    
}
