<?php

namespace App\Controller\Admin;

use App\Entity\JobOffer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class JobOfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobOffer::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('jobTitle')->setLabel('Titre');
        yield TextField::new('jobLocation')->setLabel('Adresse');
        yield ChoiceField::new('contractType')->setChoices([
            'CDI' => 'CDI',
            'CDD' => 'CDD',
            'Intérim' => 'Intérim',
            'Stage' => 'Stage',
            'Alternance' => 'Alternance',
        ])->setLabel('Contrat');
        yield TextField::new('jobDescription')->setLabel('Description');
        yield TextField::new('candidateExperience')->setLabel('Expérience requise');
        yield TextField::new('workingHours')->setLabel('Horaires');
        yield TextField::new('salary')->setLabel('Salaire');
        yield BooleanField::new('publishValidation')->setLabel('Validation');
    }
}
