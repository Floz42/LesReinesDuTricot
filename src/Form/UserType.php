<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\ImageProfile;
use App\Form\ImageProfileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => '*Pseudo :'])
            ->add('firstname', TextType::class, ['label' => '*Prénom :'])
            ->add('lastname', TextType::class, ['label' => "*Nom :"])
            ->add('email', EmailType::class, ['label' => '*E-mail :'])
            ->add('phoneNumber', TextType::class, ['label' => '*Téléphone :'])
            ->add('password', PasswordType::class, ['label' => '*Mot de passe :'])
            ->add('verifPassword', PasswordType::class, ['label' => '*Vérification (mot de passe) :'])
            ->add('imageProfile', ImageProfileType::class, [
                'data_class' => ImageProfile::class,
                'translation_domain' => 'forms',
                'required' => false,
                'label' => false
            ])
            ->add('address', TextType::class, ['label' => '*Addresse :'])
            ->add('city', TextType::class, ['label' => '*Ville :'])
            ->add('postalCode', NumberType::class, ['label' => '*Code postale :'])
            ->add('receiveNewsLetter', CheckboxType::class, [
                'required' => false,
                'label' => "S'inscrire à la Newsletter des ReinesDuTricot (exclusivement) ?",
                'attr'=> [
                    'checked' => true
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false
        ]);
    }
}
