<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/", name="accueil")
     */
    public function index(): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findBySelected(1);
        return $this->render('accueil/index.html.twig',[
            'products'=>$products
        ]);
    }
}
