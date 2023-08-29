<?php

namespace App\Form;

use App\Entity\JobOffer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class JobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jobTitle', TextType::class, [
                'label' => 'Titre',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner un titre']),
                    new Length(['max' => 100, 'maxMessage' => 'Le titre doit faire moins de 100 caractères'])
                ]
            ])
            ->add('jobLocation', TextType::class, [
                'label' => 'Lieu de travail',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message'=> 'Veuillez renseigner le lieu de travail']),
                    new Length(['max' => 255, 'maxMessage' => 'Le lieu de travail doit faire moins de 255 caractères'])
                ]
            ])
            ->add('contractType', ChoiceType::class, [
                'label' => 'Type de contrat',
                'required' => true,
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Intérim' => 'Intérim',
                    'Stage' => 'Stage',
                    'Alternance' => 'Alternance'
                ],
                'placeholder' => 'Sélection'
            ])
            ->add('jobDescription', TextType::class, [
                'label' => 'Description',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner la description du poste']),
                    new Length(['max' => 2000, 'maxMessage' => 'La description du poste doit faire moins de 2000 caractères'])
                ]
            ])
            ->add('candidateExperience', TextType::class, [
                'label' => 'Expérience requise',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner l\expérience requise pour ce poste']),
                    new Length(['max' => 500, 'maxMessage' => 'L\'expérience requise doit comporter moins de 500 caractères'])
                ]
            ])
            ->add('workingHours', TextType::class, [
                'label' => 'Horaires',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner les horaires de ce poste']),
                    new Length(['max' => 150, 'maxMessage' => 'Les horaires doivent comporter moins de 150 caractères'])
                ]
            ])
            ->add('salary', TextType::class, [
                'label' => 'Salaire',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez renseigner un salaire pour ce poste']),
                    new Length(['max' => 50, 'maxMessage' => 'Le salaire doit comporter moins de 50 caractères'])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer l\'annonce'
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobOffer::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'job_title'
        ]);
    }
}
