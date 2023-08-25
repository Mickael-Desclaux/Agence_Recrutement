<?php

namespace App\Controller\Admin;

use App\Entity\CandidateProfile;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CandidateProfileCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CandidateProfile::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('lastName')->setLabel('Nom de famille');
        yield TextField::new('firstName')->setLabel('Prénom');
        yield TextField::new('resume')->setLabel('CV');
    }
}
