<?php

namespace App\UserBundle\Handler;

use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\Security\Core\Exception\AuthenticationException;


class LogoutHandler implements LogoutSuccessHandlerInterface
{
    
    protected $facebook;
    protected $router;
    protected $security;
    
    public function __construct(\BaseFacebook $facebook, RouterInterface $router,SecurityContext $security)
    {
        $this->facebook = $facebook;
        $this->router = $router;
        $this->security = $security;
    }
    public function onLogoutSuccess(Request $request)
    {
        $this->facebook->destroySession();
        return new RedirectResponse($this->router->generate('_app_logout_redirect'));
    }
    
}