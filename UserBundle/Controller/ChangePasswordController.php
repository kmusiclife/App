<?php

namespace App\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ChangePasswordController extends Controller
{
    
    public function changePasswordAction()
    {
        
        $user = $this->getUser();
        
        if(is_object($user)){
            if(!$user->getPassword()){
                return $this->redirect( $this->generateUrl('app_user_set_password') );
            }
        }
        $form = $this->container->get('fos_user.change_password.form');
        $formHandler = $this->container->get('fos_user.change_password.form.handler');
        
        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('fos_user_success', 'change_password.flash.success');
            return new RedirectResponse($this->getRedirectionUrl($user));
        }

        return $this->container->get('templating')->renderResponse(
            'AppUserBundle:ChangePassword:changePassword.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView())
        );
        
    }
    protected function getRedirectionUrl($user)
    {
        return $this->container->get('router')->generate('fos_user_profile_show');
    }
    protected function setFlash($action, $value)
    {
        $this->container->get('session')->setFlash($action, $value);
    }    
}
