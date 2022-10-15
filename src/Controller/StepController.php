<?php

namespace App\Controller;

use App\Entity\Step;
use App\Entity\Project;
use App\Form\StepFormType;
use App\Form\ProjectFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/step')]
class StepController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {  
        $this->em = $em;
    }

    #[Route('', name: 'app_step_list', methods: 'GET')]
    public function list(): Response
    {
        return $this->render('step/index.html.twig');
    }

    #[Route('/create', name: 'app_step_create', methods: 'GET|POST')] // OK form & twig
    public function create(Request $request): Response
    {
        $user_current = $this->getUser();
        if(!$user_current) {

            $this->addFlash('info', 'Pour ajouter une étape à votre projet merci de vous connecter.');

            return $this->redirectToRoute('app_login');

        } else {

            $step = new Step();

            $form = $this->createForm(StepFormType::class, $step);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) { 
                $this->em->persist($step);
                $this->em->flush();

                $this->addFlash('success', 'L\'étape a bien été ajoutée à votre projet !');

                return $this->redirectToRoute('app_project_show', ['id' => $step->getProjectId()]);
            }

        }

        return $this->render('step/create.html.twig', [
            'form' => $form->createView()
        ]); 
    }

    #[Route('/{id}/edit', name: 'app_step_edit', methods: 'GET|POST')] // OK form & twig
    public function edit(): Response
    {
        return $this->render('step/edit.html.twig');
    }
}
