<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseLivraisonType;
use App\Service\Cart\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(CartService $cartService): Response
    {
        $dataPanier = $cartService->getFullCart();
        $total = $cartService->getTotal();
        return $this->render('cart/index.html.twig', [
            "dpanier"=> $dataPanier,
            "total"=> $total
        ]);
    }


    /**
     * @Route("/add/{id}", name="cart_add")
     */
    public function add($id, CartService $cartService, Request $request){

        $cartService->add($id);
        $this->addFlash('success', "Le produit a été rajouté au panier");

        return $this->redirectToRoute("app_cart");

    }

    /**
     * @Route("/supp/{id}", name="cart_supp")
     */
    public function suppP($id,CartService $cartService, Request $request){

        $cartService->remove($id);
        $this->addFlash('success', "Le produit a été supprimé du panier");
        return $this->redirectToRoute('app_cart');
    }

    /**
     * @Route("/minus/{id}", name="cart_minus")
     */
    public function SuppPanier($id,CartService $cartService){

      $cartService->minus($id);

        return $this->redirectToRoute("app_cart");

    }

    /**
     * @Route("/adresscart", name="adress")
     */
    public function adress(SessionInterface $session, CartService $cartService, EntityManagerInterface $entityManager, Request $request, Security $security): Response
    {
        $dataPanier = $cartService->getFullCart();
        $total = $cartService->getTotal();
        $user = $security->getUser();

        //Creer formulaire adresse
        $adresse = new Adresse();
        $form = $this->createForm(AdresseLivraisonType::class, $adresse);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $adresse->setUser($user);
            $entityManager->persist($adresse);
            $entityManager->flush();
            $route = $request->headers->get('referer');
            return $this->redirect($route);
        }
        if (!empty($user)){
            $adresseL = $user->getAdresses();
        }
        else{
            $adresseL = null;
        }

        //Récuperer l'adresse de livraison
        if ($request->request->count()> 0){
            $id = $request->request->get('adresseV');
            $adresseV = $entityManager->getRepository(Adresse::class)->find($id);
            $session->set('adresse', $adresseV);
            return $this->redirectToRoute('app_paiement');
        }
        return $this->render('cart/adresscart.html.twig', [
            "dpanier"=> $dataPanier,
            "total"=> $total,
            "adresses"=> $adresseL,
            "adresseForm" => $form->createView()
        ]);
    }

}
