<?php

namespace App\Controller\Admin;

use App\Entity\Application;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ApplicationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Application::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('jobOfferID')->setLabel('ID annonce');
        yield IntegerField::new('candidateID')->setLabel('ID du candidat');
        yield BooleanField::new('applicationValidation')->setLabel('Validation');
    }
}
