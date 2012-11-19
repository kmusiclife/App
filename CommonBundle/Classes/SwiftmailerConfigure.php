<?php

namespace App\CommonBundle\Classes;

class SwiftmailerConfigure
{
    public function __construct()
    {
        $this->configureSwiftMailer();
    }
    protected function configureSwiftMailer(){
        \Swift::init(function () {
            \Swift_DependencyContainer::getInstance()
                ->register('mime.qpheaderencoder')
                ->asAliasOf('mime.base64headerencoder');
            \Swift_Preferences::getInstance()->setCharset('iso-2022-jp');
        });
    }
    
}