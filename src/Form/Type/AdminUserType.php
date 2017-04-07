<?php

namespace Platform\Bundle\AdminBundle\Form\Type;

use Sylius\Bundle\UserBundle\Form\Type\UserType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminUserType extends UserType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'admin_platform.form.user.first_name',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'admin_platform.form.user.last_name',
            ])
            ->add('localeCode', LocaleType::class, [
                'label' => 'sylius.ui.locale',
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'platform_admin_user';
    }
}
