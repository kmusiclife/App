<?php

namespace App\UserBundle\Form\Model;
use Symfony\Component\Validator\Constraints as Assert;

class SetPassword
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $new;
}
