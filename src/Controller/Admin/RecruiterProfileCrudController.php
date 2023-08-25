<?php

namespace App\Controller\Admin;

use App\Entity\RecruiterProfile;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RecruiterProfileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecruiterProfile::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('companyName')->setLabel('Nom de la société');
        yield TextField::new('companyAdress')->setLabel('Adresse');
    }
}
