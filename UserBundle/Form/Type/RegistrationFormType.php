<?php

namespace App\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // parent::buildForm($builder, $options);

        // add your custom field
        
        $builder->add('username', 'text', array('attr'=>array('style'=>'width: 200px;', 'placeholder'=>'nickname'), 'label'=>'profile.show.username', 'translation_domain'=>'AppUserBundle'));
        $builder->add('email', 'text', array('attr'=>array('style'=>'width: 200px;', 'placeholder'=>'name@example.com'), 'label'=>'profile.show.email', 'translation_domain'=>'AppUserBundle'));
        
        $builder->add('firstname', 'text', array('attr'=>array('style'=>'width: 100px;'), 'label'=>'profile.show.firstname', 'translation_domain'=>'AppUserBundle'));
        $builder->add('lastname', 'text', array('attr'=>array('style'=>'width: 100px;'), 'label'=>'profile.show.lastname', 'translation_domain'=>'AppUserBundle'));
        
        $builder->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'AppUserBundle'),
                'first_options' => array('attr'=>array('style'=>'width: 100px;'), 'label' => 'profile.show.password'),
                'second_options' => array('attr'=>array('style'=>'width: 100px;'), 'label' => 'profile.show.password_confirm'),
                'invalid_message' => 'fos_user.password.mismatch',
            ));

        $builder->add('is_magazine', 'checkbox', array('attr'=>array('style'=>'width: 100px;'), 'label'=>'profile.show.is_magazine', 'translation_domain'=>'AppUserBundle'));
        

    }

    public function getName()
    {
        return 'app_user_registration';
    }
}