<?php

namespace App\Form;

use App\Entity\UpdatePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('verificationCode', TextType::class, ['label' => "Code de vérification :"])
            ->add('newPassword', PasswordType::class, ['label' => "Nouveau mot de passe :"])
            ->add('confirmNewPassword', PasswordType::class, ['label' => "Confirmation mot de passe :"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UpdatePassword::class
        ]);
    }
}
