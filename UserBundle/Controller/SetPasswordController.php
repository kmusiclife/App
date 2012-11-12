<?php

namespace App\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\UserBundle\Form\Type\SetPasswordFormType;
use App\UserBundle\Form\Model\SetPassword;

class SetPasswordController extends Controller
{
    public function editAction()
    {
        
        $user = $this->getUser();
        
        if(is_object($user)){
            if($user->getPassword()){
                $redirect_url = $this->getRequest()->headers->get('referer');
                if(!$redirect_url) $redirect_url = $this->generateUrl('fos_user_profile_show');
                return $this->redirect( $redirect_url );
            }
        }
        
        $setPassword = new SetPassword();
        $form = $this->createForm(new SetPasswordFormType(), $setPassword);
        
        return $this->render(
            'AppUserBundle:SetPassword:set_password.html.twig',
            array('form' => $form->createView())
        );
        
    }
    public function updateAction()
    {

        $request = $this->getRequest();
        
        $setPassword = new SetPassword();
        $form = $this->createForm(new SetPasswordFormType(), $setPassword);
        $form->bindRequest($request);
        
        if($form->isValid()){
            
            $user = $this->getUser();
            $user->setPlainPassword($form->getData()->new);
            
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);
            
            $this->get('session')->setFlash('notice', 'app_user.user.notice.set_password.success');
            return $this->redirect($this->generateUrl('fos_user_profile_show'));

        }
        return $this->render(
            'AppUserBundle:SetPassword:set_password.html.twig',
            array('form' => $form->createView())
        );
        
        
    }
}
