<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController

{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/panier", name="cart")
     */
    public function index(Cart $cart): Response
    {
        $cartItem = [];
        if($cart->get()){
            foreach ($cart->get() as $id => $quantity){

                $cartItem[] = [
                    'product' => $this->entityManager->getRepository(Product::class)->findOneById($id),
                    'quantity' => $quantity
                ];

            }
        }

        return $this->render('cart/index.html.twig',[
            'cart'=>$cartItem
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add")
     */
    public function add(Cart $cart, $id): Response
    {
        $cart->add($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name="remove")
     */
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('product');
    }

    /**
     * @Route("/cart/delete/{id}", name="delete")
     */
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/minus/{id}", name="minus")
     */
    public function minus(Cart $cart, $id): Response
    {
        $cart->minus($id);
        return $this->redirectToRoute('cart');
    }
}
