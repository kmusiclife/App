<?php
// symfony2/src/Application/Sonata/UserBundle/EventListener/UserListener.php

namespace App\CommonBundle\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class LocaleListener
{
    public function setLocaleForUnauthenticatedUser(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $request->setLocale($request->getPreferredLanguage());
    }
    public function setLocaleForAuthenticatedUser(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $request = $event->getRequest();
        
        if(method_exists($user, 'getLocale')){
            $locale = $user->getLocale();
        } else $locale = null;
        
        $request->getSession()->set('_locale', $locale);
        $request->setLocale($locale);
        $request->setDefaultLocale($locale);
    }
}