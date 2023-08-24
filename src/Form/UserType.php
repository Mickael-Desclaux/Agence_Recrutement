<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class,
            ['label' => 'E-Mail',
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'Veuillez renseigner une adresse mail'])]])

            ->add('role', ChoiceType::class, [
                'label' => 'Vous êtes ...',
                'required' => true,
                'choices' => [
                    'Candidat' => 'ROLE_CANDIDATE',
                    'Recruteur' => 'ROLE_RECRUITER'
                ]
            ])

            ->add('password', PasswordType::class,
            ['label' => 'Mot de passe',
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'Veuillez renseigner un mot de passe'])]])

            ->add('submit', SubmitType::class,
            ['label' => 'Valider'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'user_item'
        ]);
    }
}
