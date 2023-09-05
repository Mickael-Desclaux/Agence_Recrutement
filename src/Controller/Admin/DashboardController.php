<?php

namespace App\Controller\Admin;

use App\Entity\Application;
use App\Entity\CandidateProfile;
use App\Entity\JobOffer;
use App\Entity\RecruiterProfile;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TRT Conseil');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Page d\'accueil', 'fa fa-home', '/');
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Offres d\'emplois', 'fas fa-list', JobOffer::class);
        yield MenuItem::linkToCrud('Candidatures', 'fas fa-list', Application::class);
        yield MenuItem::linkToCrud('Profils Candidats', 'fas fa-user', CandidateProfile::class);
        yield MenuItem::linkToCrud('Profils Recruteurs', 'fa-solid fa-building', RecruiterProfile::class);
        yield MenuItem::linkToLogout('Déconnexion', 'fa-sharp fa-solid fa-circle-xmark');
    }
}
