<?php
namespace App\Service\Cart;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService{

    protected $session;
    protected $productRepository;

    public function __construct(SessionInterface $session, ProduitRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }


    public function add(int $id){
        $panier = $this->session->get('panier', []);

        if(!empty($panier[$id])){
            $panier[$id]++;

        }else{
            $panier[$id] = 1;

        }

        $this->session->set("panier", $panier);

    }

    public function remove(int $id){
        $panier = $this->session->get("panier", []);
        if(!empty($panier[$id])) {
            unset($panier[$id]);
        }
            $this->session->set('panier', $panier);
    }

    public function minus(int $id){
        $panier = $this->session->get("panier", []);
        if(!empty($panier[$id])){
            if ($panier[$id]>1){
                $panier[$id]--;
            } else{
                unset($panier[$id]);
            }
        }
        $this->session->set('panier', $panier);
    }

    public function getFullCart(): array{
        $panier = $this->session->get('panier', []);
        $dataPanier = [];
        $quant = 0;

        foreach ($panier as $id => $quantite ){
            $dataPanier[] = [
                "produit" => $this->productRepository->find($id),
                "quantite" => $quantite
            ];
            $quant += $quantite;}

        $this->session->set('quant', $quant);
            return $dataPanier;
    }

    public function getTotal(): float {
        $total = 0;

        foreach ($this->getFullCart() as $item){
            $total += $item['produit']->getMenu()->getPrix() * $item['quantite'];
        }
        return $total;
    }

}