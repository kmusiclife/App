<?php

namespace App\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class ProfileFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('username', 'text', array('label'=>'profile.show.username', 'translation_domain' => 'AppUserBundle'));
        $builder->add('email', 'email', array('label'=>'profile.show.email', 'translation_domain' => 'AppUserBundle'));
        $builder->add('firstname', 'text', array('label'=>'profile.show.firstname', 'translation_domain' => 'AppUserBundle'));
        $builder->add('lastname', 'text', array('label'=>'profile.show.lastname', 'translation_domain' => 'AppUserBundle'));
        $builder->add('bio', 'textarea', array('label'=>'profile.show.bio', 'translation_domain' => 'AppUserBundle'));
        
        // parent::buildForm($builder, $options);
        
    }
    public function getName()
    {
        return 'app_user_profile';
    }
        
}