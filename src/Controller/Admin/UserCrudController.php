<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserCrudController extends AbstractCrudController implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['hashPassword']
        ];
    }

    public function hashPassword(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof PasswordAuthenticatedUserInterface)) {
            return;
        }

        $plainPassword = $entity->getPassword();
        $hashedPassword = $this->passwordHasher->hashPassword($entity, $plainPassword);
        $entity->setPassword($hashedPassword);
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield EmailField::new('email')->setLabel('Adresse E-Mail');
        yield TextField::new('password')->setLabel('Mot de passe')->hideOnIndex();
        yield ChoiceField::new('role')->setChoices([
            'ROLE_CONSULTANT' => 'ROLE_CONSULTANT',
            'ROLE_CANDIDATE_VALID' => 'ROLE_CANDIDATE_VALID',
            'ROLE_CANDIDATE' => 'ROLE_CANDIDATE',
            'ROLE_RECRUITER_VALID' => 'ROLE_RECRUITER_VALID',
            'ROLE_RECRUITER' => 'ROLE_RECRUITER',
        ])
            ->setLabel('Rôle');
        yield BooleanField::new('userValidation')->setLabel('Validation');
    }

    public function configureActions(Actions $actions): Actions
    {
        $validateUser = Action::new('validateUser', 'Valider', 'fa fa-check')
            ->linkToCrudAction('validateUser');

        return $actions
            ->add('index', $validateUser);
    }

    public function validateUser(AdminContext $context): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $user = $context->getEntity()->getInstance();

        $user->validateUser();
        $this->entityManager->flush();

        $this->addFlash('success', 'Utilisateur validé avec succès.');

        return $this->redirect($context->getReferrer());
    }
}
