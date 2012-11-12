<?php

namespace App\UserBundle\Form\Type;

use Symfony\Component\Security\Core\Validator\Constraint\UserPassword;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ChangePasswordFormType as BaseType;

class ChangePasswordFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('current_password', 'password', array(
            'label' => 'form.current_password',
            'translation_domain' => 'FOSUserBundle',
            'mapped' => false,
            'constraints' => new UserPassword(),
        ));
        $builder->add('new', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.new_password'),
            'second_options' => array('label' => 'form.new_password_confirmation'),
        ));
        
    }
    public function getName()
    {
        return 'app_user_change_password';
    }
    
}