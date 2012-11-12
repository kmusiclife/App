<?php

namespace App\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    
    public function checkFacebookAction(){
        // dummy
    }
    public function loginByFacebookAction(){
        
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
        return $this->render('AppUserBundle:Login:index.html.twig', array('loginUrl'=>$loginUrl));

    }
    public function disconnectFacebookAction(){
        
        if(!$this->getUser()->getPassword()){
            $this->get('session')->setFlash('notice', 'app_user.facebook.notice.user.disconnect_require_password');
            return $this->redirect($this->generateUrl('app_user_set_password'));
        }
        try{
            $user_manager = $this->get('fos_user.user_manager');
            $this->getUser()->setFacebookId('');
            $this->getUser()->setFacebookAccessToken('');
            $this->getUser()->removeRole('ROLE_FACEBOOK');
            $user_manager->updateUser($this->getUser());
            
        } catch (FacebookApiException $e) {
            die('Error: FOSFacebook api error.');
        } 
        $this->get('session')->setFlash('notice', 'app_user.facebook.notice.user.disconnected');
        return $this->redirect($this->generateUrl('fos_user_profile_show'));

    }
    public function connectFacebookAction(){

        if(!$this->getUser()->hasRole('ROLE_USER')){
            $this->get('session')->setFlash('notice', 'app_user.facebook.notice.user.require_auth');
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
        }
        
        $fbid = null;
        
        try {
            
            $fbdata = $this->get('fos_facebook.api')->api('/me');
            $fbid = $fbdata['id'];
            
            $user_manager = $this->get('fos_user.user_manager');
            $user = $user_manager->findUserBy(array('facebookId' => $fbid));
            
            if(!empty($user)){
                $this->get('session')->setFlash('notice', 'app_user.facebook.notice.user.already_authed');
                return $this->redirect($this->generateUrl('fos_user_profile_show'));
            }
            
            
        } catch (FacebookApiException $e) {
            die('Error: FOSFacebook api error.');
        }
        if ($fbid) {
            
            $this->getUser()->setFacebookId($fbdata['id']);
            $this->getUser()->addRole('ROLE_FACEBOOK');
            
            $this->get('fos_facebook.api')->setExtendedAccessToken();
            $this->getUser()->setFacebookAccessToken($this->get('fos_facebook.api')->getAccessToken());
            
            $user_manager->updateUser($this->getUser());
            $this->get('session')->setFlash('notice', 'app_user.facebook.notice.user.connected');
            
            return $this->redirect($this->generateUrl('fos_user_profile_show'));
            
        }
        
    }
}
