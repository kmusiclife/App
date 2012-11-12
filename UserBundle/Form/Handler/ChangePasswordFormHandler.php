<?php

namespace App\UserBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\ChangePasswordFormHandler as BaseHandler;
use FOS\UserBundle\Model\UserInterface;

class ChangePasswordFormHandler extends BaseHandler
{
    public function process(UserInterface $user)
    {
        parent::process($user);
    }
    protected function onSuccess(UserInterface $user)
    {
        $this->userManager->updateUser($user);
    }
}
