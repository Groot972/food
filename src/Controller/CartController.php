<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        $dataPanier = [];
        $total = 0;

        foreach ($panier as $id => $quantite ){
            $product = $entityManager->getRepository(Produit::class)->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite,
                'sstotal' => $product->getMenu()->getPrix() * $quantite
            ];

            $total += $product->getMenu()->getPrix() * $quantite;
        }



        return $this->render('cart/index.html.twig', [
            "dpanier"=> $dataPanier,
            "total"=> $total
        ]);
    }


    /**
     * @Route("/add/{id}", name="cart_add")
     */
    public function add($id, SessionInterface $session){

        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]++;

        }else{
            $panier[$id] = 1;

        }

        $session->set("panier", $panier);
        return $this->redirectToRoute("app_cart");

    }
}
