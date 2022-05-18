<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Entity\Commandes;
use App\Entity\LigneDeCommande;
use App\Entity\Menu;
use App\Entity\Produit;
use App\Entity\User;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(SessionInterface $session): Response
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
     * @Route("/commander", name="app_shop")
     */
    public function shop(): Response
    {

        return $this->render('home/shop.html.twig');
    }

    /**
     * @Route("/monespace", name="app_espace")
     */
    public function monespace(EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security>$this->getUser()->getUserIdentifier();
        $commande = $entityManager->getRepository(Commandes::class)->findBy(['client'=> $user]);

        return $this->render('user/index.html.twig', [
            'data' => $commande
        ]);
    }


    /**
     * @Route("/paiement", name="app_paiement")
     */
    public function paiementA(SessionInterface $session, Request $request){


        $prix = $session->get('total');
        $adresse = $session->get('adresse');
        if (!empty($adresse)){
            Stripe::setApiKey('sk_test_pVAR5MBWgyZM4BtqxzMlfiVM00WjXovWYD');

            $intent= PaymentIntent::create([
                'amount' => $prix*100,
                'currency' => 'eur',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],

            ]);
            $secret = $intent->client_secret;

            }else{
            $this->redirectToRoute('app_cart');
        }

        return $this->render('cart/shop.html.twig', [
            'secret'=> $secret
        ]);
    }



    /**
     * @Route("/notifsucces", name="app_succes")
     */
    public function succes(SessionInterface $session, CartService $cartService, EntityManagerInterface $entityManager, Security $security): Response
    {
        $dataPanier = $cartService->getFullCart();
        $total = $cartService->getTotal();
        $user = $security>$this->getUser()->getUserIdentifier();
        $client = $entityManager->getRepository(User::class)->find($user);
        $adress = $entityManager->getRepository(Adresse::class)->find($session->get('adresse'));

        $commande = new Commandes();
        $commande->setEtat('En cours');
        $commande->setTotal($total);
        $commande->setClient($client);
        $commande->setAdresse($adress);
        $commande->setDate(new \DateTime());
        $entityManager->persist($commande);
        foreach($dataPanier as $d)
        {
            $produit = $entityManager->getRepository(Produit::class)->find($d['produit']->getId());
            //creer ligne de commande
            $ldc = new LigneDeCommande();
            $ldc->setQuantity($d['quantite']);
            $ldc->setProduit($produit);
            $ldc->setCommandes($commande);
            $entityManager->persist($ldc);
        }
        $entityManager->flush();
        $session->set("panier", []);

        return $this->render('cart/succes.html.twig');
    }

    /**
     * @Route("/notifcancel", name="app_cancel")
     */
    public function cancel(): Response
    {

        return $this->render('cart/cancel.html.twig');
    }
}
