<?php

namespace App\Controller;

use App\Entity\Step;
use App\Form\StepFormType;
use App\Repository\ProjectRepository;
use App\Repository\StepRepository;
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

    #[Route('/{id_project}/create', name: 'app_step_create', methods: 'GET|POST')] // OK form & twig
    public function create(Request $request, ProjectRepository $project_repo, StepRepository $step_repo): Response
    {
        $project = $project_repo->find($request->attributes->get('id_project'));
        $nb_step = $step_repo->findCountStepsInProject($project->getId()) + 1;

        $user_current = $this->getUser();
        if(!$user_current) {

            $this->addFlash('info', 'Pour ajouter une étape au projet merci de vous connecter.');

            return $this->redirectToRoute('app_login');

        } else {
            
            $step = new Step();
            $form = $this->createForm(StepFormType::class, $step, [
                'nb_steps_already_existing' => $nb_step
            ]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) { 
                $step->setProject($project);  
                $step->setCreator($user_current);
                $step->setCreatedAt(new \DateTimeImmutable);
                $step->setUpdatedAt(new \DateTimeImmutable);

                $this->em->persist($step);
                $this->em->flush();

                $this->addFlash('success', 'L\'étape a bien été ajoutée à votre projet !');

                $steps = $step_repo->findBy([], ['createdAt' => 'DESC']);
                return $this->redirectToRoute('app_project_show', [
                    'id' => $step->getProjectId(),
                    'steps' => $steps,
                ]);
            }

        }

        return $this->render('step/create.html.twig', [
            'nb_step' => $nb_step,
            'project' => $project,
            'form' => $form->createView()
        ]); 
    }

    #[Route('/{id}/edit', name: 'app_step_edit', methods: 'GET|POST')] // OK form & twig
    public function edit(): Response
    {
        return $this->render('step/edit.html.twig');
    }

    #[Route('/{id}/delete', name: 'app_step_delete', methods: 'DELETE')] // OK form & twig
    public function delete(Step $step, Request $request, ProjectRepository $repo): Response
    {
        $user_current = $this->getUser();
        $project = $repo->find($step->getProjectId());
        if(!$user_current || $user_current != $project->getCreator()) {
            $this->addFlash('warning', 'Vous ne pouvez pas supprimer le projet.');

            return $this->render('project/show.html.twig', compact('project'));
        } else {
            $token = $request->request->get('csrf_token');

            if($this->isCsrfTokenValid('project_deletion_' . $project->getId(), $token)) {
                $this->em->remove($project);
                $this->em->flush();
            }

            $this->addFlash('info', 'Projet supprimé !');
            
            return $this->redirectToRoute('app_user_home');
        }   
    }
}
