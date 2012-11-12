<?php

namespace App\UserBundle\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\Security\Core\Exception\AuthenticationException;


class AuthenticationHandler
implements AuthenticationSuccessHandlerInterface,
           AuthenticationFailureHandlerInterface
{
    
    protected $router;
    protected $security;
    protected $userManager;
    protected $service_container;
    protected $facebook;
    
    public function __construct(RouterInterface $router,SecurityContext $security, $userManager, $service_container, \BaseFacebook $facebook)
    {
        $this->router = $router;
        $this->security = $security;
        $this->userManager = $userManager;
        $this->service_container = $service_container;
        $this->facebook = $facebook;
        
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        if ($request->isXmlHttpRequest()) {
            $result = array('success' => true);
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        if(!$token->getUser()->getLocale()){
            $token->getUser()->setLocale($request->getPreferredLanguage());
            $this->userManager->updateUser($token->getUser());
        }
        return new RedirectResponse($this->router->generate('_app_login_redirect'));
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        
        if ($request->isXmlHttpRequest()) {
            $result = array('success' => false, 'message' => $exception->getMessage());
            $response = new Response(json_encode($result));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        return new Response();
    }
}