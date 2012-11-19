<?php

namespace App\UserBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\ProfileFormHandler as BaseHandler;
use FOS\UserBundle\Model\UserInterface;

class ProfileFormHandler extends BaseHandler
{
      public function process(UserInterface $user)
      {
            return parent::process($user); // sound better of course : )
            
      }
      protected function onSuccess(UserInterface $user)
      {
          $this->userManager->updateUser($user);
      }
}
