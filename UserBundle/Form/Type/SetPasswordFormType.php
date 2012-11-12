<?php

namespace App\UserBundle\Form\Type;

use Symfony\Component\Security\Core\Validator\Constraint\UserPassword;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType as BaseType;

class SetPasswordFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('new', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.new_password'),
            'second_options' => array('label' => 'form.new_password_confirmation'),
        ));
        
    }
    public function getName()
    {
        return 'app_user_set_password';
    }
    
}