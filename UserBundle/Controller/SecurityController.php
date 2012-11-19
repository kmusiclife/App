<?php

namespace App\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends Controller
{
    
    public function loginAction()
    {

        $loginUrl = $this->get('fos_facebook.api')->getLoginUrl();
        $fbid = $this->get('fos_facebook.api')->getUser();
        
        if($fbid){

            try {
                
                $user = $this->get('my.facebook.user')->loadUserByUsername($fbid);
                
            } catch (UsernameNotFoundException $e){

                $this->get('fos_facebook.api')->destroySession();
                return new RedirectResponse($this->generateUrl('fos_user_security_login'));

            }
            try {
                
                $this->container->get('fos_user.security.login_manager')->loginUser(
                    $this->container->getParameter('fos_user.firewall_name'),
                    $user,
                    new RedirectResponse($this->generateUrl('home'))
                );

                return new RedirectResponse($this->generateUrl('home'));

            } catch (AccountStatusException $ex) {
                // Error
            }
            return new RedirectResponse($this->generateUrl('home'));

        }
        $loginUrl = $this->get('fos_facebook.api')->getLoginUrl(
            array(
                'scope' => implode(',', $this->container->getParameter('fos_facebook.permissions'))
            )
        ); 

        $request = $this->container->get('request');
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            $error = $error->getMessage();
        }
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return $this->renderLogin(array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'csrf_token'    => $csrfToken,
            'loginUrl'      => $loginUrl,
        ));
    }
    protected function renderLogin(array $data)
    {
        $template = sprintf('FOSUserBundle:Security:login.html.%s', $this->container->getParameter('fos_user.template.engine'));
        return $this->container->get('templating')->renderResponse($template, $data);
    }
    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }


}
