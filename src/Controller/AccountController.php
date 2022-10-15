<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/account')]
class AccountController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {  
        $this->em = $em;
    }
    
    #[Route('', name: 'app_account', methods: 'GET')]
    public function show(): Response
    {
        return $this->render('account/show.html.twig');
    }

    #[Route('/edit', name: 'app_account_edit', methods: 'GET|POST')]
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        if(!$user) {
            $this->addFlash('warning', 'You cannot edit the profile.');

            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        return $this->render('account/edit.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    #[Route('/change-password', name: 'app_account_change', methods: 'GET|POST')]
    public function change(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {   
        if(!$this->getUser()) {
            $this->addFlash('warning', 'You cannot change password');

            return $this->redirectToRoute('app_login');
        }
        
        $user = $this->getUser();
        //dd($user);
        $form = $this->createForm(ChangePasswordFormType::class, null, [
            'current_password_is_required' => true
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // encode the plain password
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
            $this->em->flush();

            $this->addFlash('success', 'Password successfully updated !');
            return $this->redirectToRoute('app_account');
            
        }
        return $this->render('account/change.html.twig', [
            'resetPasswordForm' => $form->createView(),
        ]);
    }
}
