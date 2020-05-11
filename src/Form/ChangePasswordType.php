<?php

namespace App\Form;

use App\Entity\UpdatePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, ['label' => "Mot de passe actuel :"])
            ->add('newPassword', PasswordType::class, ['label' => "Nouveau mot de passe :"])
            ->add('confirmNewPassword', PasswordType::class, ['label' => "Confirmation nouveau mot de passe :"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => UpdatePassword::class
        ]);
    }
}
