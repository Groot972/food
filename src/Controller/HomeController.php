<?php

namespace App\Controller;

use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/produits", name="app_produits")
     */
    public function produits(EntityManagerInterface $entityManager): Response
    {
        $menu = $entityManager->getRepository(Menu::class)->findAll();

        return $this->render('home/produits.html.twig', [
            'menu' => $menu,
        ]);
    }

    /**
     * @Route("/shop", name="app_shop")
     */
    public function shop(): Response
    {
        return $this->render('home/shop.html.twig');
    }
}
