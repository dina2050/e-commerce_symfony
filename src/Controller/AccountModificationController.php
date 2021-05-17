<?php

namespace App\Controller;

use App\Form\ChangePassType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountModificationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/compte/modification", name="account_modification")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $warning=null;
        $user=$this->getUser();
        $form=$this->createForm(ChangePassType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $oldPassword=$form->get('oldPassword')->getData();

            if($encoder->isPasswordValid($user, $oldPassword)){
                $newPassword=$form->get('newPassword')->getData();
                $password=$encoder->encodePassword($user, $newPassword);

                $user->setPassword($password);
                $this->entityManager->flush();
                $warning="Votre mot de passe à bien été modifié";
            }
            else{
                $warning="Mot de passe incorrect";
            }
        }
        return $this->render('account/modify.html.twig', [
            'form'=>$form->createView(),
            'warning'=>$warning
        ]);
    }
}
