<?php

namespace App\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class ProfileFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('username', 'text', array('attr'=>array('style'=>'', 'class'=>'span3'), 'label'=>'profile.show.username', 'translation_domain' => 'AppUserBundle'));
        $builder->add('email', 'email', array('attr'=>array('style'=>'', 'class'=>'span4'), 'label'=>'profile.show.email', 'translation_domain' => 'AppUserBundle'));
        $builder->add('firstname', 'text', array('attr'=>array('style'=>'', 'class'=>'span4'), 'label'=>'profile.show.firstname', 'translation_domain' => 'AppUserBundle'));
        $builder->add('lastname', 'text', array('attr'=>array('style'=>'', 'class'=>'span4'), 'label'=>'profile.show.lastname', 'translation_domain' => 'AppUserBundle'));
        $builder->add('bio', 'textarea', array('attr'=>array('style'=>'height: 250px;', 'class'=>'span6'), 'label'=>'profile.show.bio', 'translation_domain' => 'AppUserBundle'));
        
        $builder->add('isMagazine', 'checkbox', array('attr'=>array('style'=>''), 'label'=>'profile.show.is_magazine', 'translation_domain'=>'AppUserBundle', 'required'=>false));
        
        // parent::buildForm($builder, $options);
        
    }
    public function getName()
    {
        return 'app_user_profile';
    }
        
}