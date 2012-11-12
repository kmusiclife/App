<?php

namespace App\UserBundle\Security\User\Provider;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use \BaseFacebook;
use \FacebookApiException;

class FacebookProvider implements UserProviderInterface
{
    /**
     * @var \Facebook
     */
    protected $facebook;
    protected $userManager;
    protected $validator;

    public function __construct(BaseFacebook $facebook, $userManager, $validator)
    {
        $this->facebook = $facebook;
        $this->userManager = $userManager;
        $this->validator = $validator;
    }

    public function supportsClass($class)
    {
        return $this->userManager->supportsClass($class);
    }
    public function findUserByFbId($fbId)
    {
        return $this->userManager->findUserBy(array('facebookId' => $fbId));
    }
    public function findUserByUsername($username)
    {
        return $this->userManager->findUserBy(array('username' => $username));
    }
    public function loadUserByUsername($fbId)
    {
        
        $user = $this->findUserByFbId($fbId);
        
        try {
            $fbdata = $this->facebook->api('/me');
        } catch (FacebookApiException $e) {
            $fbdata = null;
        }

        if (!empty($fbdata)) {
            if (empty($user)) {
                
                $user = $this->userManager->createUser();
                $user->setEnabled(true);
                $user->setPassword('');
                $this->facebook->setExtendedAccessToken();
                $user->setFacebookAccessToken($this->facebook->getAccessToken());
                $user->setFBData($fbdata);
                
                $username_check_user = $this->findUserByUsername($fbdata['username']);
                if($username_check_user){
                    $user->setUsername($fbdata['id']);
                }
                
            } else {
                $user->setFBData($fbdata);
            }
            // you must add "Facebook" validation_groups at App/UserBundle/Resources/config/validation.xml
            if (count($this->validator->validate($user, 'Facebook'))) {
                throw new UsernameNotFoundException('The facebook user could not be stored');
            }
            $this->userManager->updateUser($user);
        }

        if (empty($user)) {
            throw new UsernameNotFoundException('The user is not authenticated on facebook');
        }

        return $user;
    }
    
    public function refreshUser(UserInterface $user)
    {
        if (!$this->supportsClass(get_class($user)) || !$user->getFacebookId()) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getFacebookId());
    }
}