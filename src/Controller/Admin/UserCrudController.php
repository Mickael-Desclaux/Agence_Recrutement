<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield EmailField::new('email')->setLabel('Adresse E-Mail');
        yield TextField::new('password')->setLabel('Mot de passe')->hideOnIndex();
        yield TextField::new('role')->setLabel('Rôle');
        yield BooleanField::new('userValidation')->setLabel('Validation');
    }
}
