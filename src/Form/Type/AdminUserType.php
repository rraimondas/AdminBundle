<?php

namespace Platform\Bundle\AdminBundle\Form\Type;

use Sylius\Bundle\LocaleBundle\Form\Type\LocaleType;
use Sylius\Bundle\UserBundle\Form\Type\UserType;
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
                'label' => 'sylius.form.user.first_name',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'sylius.form.user.last_name',
            ])
            ->add('localeCode', LocaleType::class, [
                'label' => 'sylius.ui.locale',
                'placeholder' => null,
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
