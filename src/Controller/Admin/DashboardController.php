<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use App\Entity\Pizzas;
use App\Entity\Produit;
use App\Entity\Sandwichs;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use PhpParser\Node\Expr\Yield_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator ;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // rediriger vers un contrôleur CRUD

        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(MenuCrudController::class)->generateUrl());

      //  return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Food');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Accueil', 'fa fa-home', 'app_home');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        //yield MenuItem::linkToCrud('Sandwichs', 'fas fa-list', Sandwichs::class);
        yield MenuItem::subMenu('Produits','fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Ajouter un Produit', 'fas fa-plus',Produit::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les Produits', 'fas fa-eye',Produit::class)->setAction(Crud::PAGE_INDEX)
        ]);
        yield MenuItem::subMenu('Menus','fas fa-list')->setSubItems([
            MenuItem::linkToCrud('Ajouter un Menu', 'fas fa-plus',Menu::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Voir les Menus', 'fas fa-eye',Menu::class)->setAction(Crud::PAGE_INDEX)
        ]);
    }
}
