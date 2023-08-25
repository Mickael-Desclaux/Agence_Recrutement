<?php

namespace App\Form;

use App\Entity\RecruiterProfile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RecruiterProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('companyName', TextType::class,
            ['label' => 'Nom de la société',
                'constraints' => [
                    new Length(['max' => 50, 'maxMessage' => 'Le nom de la société ne doit pas dépasser 50 caractères'])
                ]])
            ->add('companyAddress', TextType::class,
                ['label' => 'Adresse de la société',
                    'constraints' => [
                        new Length(['max' => 150, 'maxMessage' => 'L\'adresse de la société ne doit pas dépasser 150 caractères'])
                    ]])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecruiterProfile::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'company_name',
        ]);
    }
}
