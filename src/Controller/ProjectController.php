<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectFormType;
use App\Repository\StepRepository;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/projects')]
class ProjectController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {  
        $this->em = $em;
    }
    
    #[Route('', name: 'app_project_list', methods: 'GET')]
    public function list(ProjectRepository $projectRepository): Response 
    {   
        $projects = $projectRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('project/list.html.twig', compact('projects'));
    }

    /*#[Route('/list', name: 'app_project_list', methods: 'GET')]
    public function list(ProjectRepository $projectRepository): Response 
    {   
        $projects = $projectRepository->findAll();
            return $this->render('project/list.html.twig', [
                'projects'  => $projects,
            ]);
    }*/

    #[Route('/create', name: 'app_project_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        $user_current = $this->getUser();
        if(!$user_current) {

            $this->addFlash('info', 'Pour ajouter un projet merci de vous connecter.');

            return $this->redirectToRoute('app_login');

        } else {

            $project = new Project();

            $form = $this->createForm(ProjectFormType::class, $project);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) { 
                $project->setCreator($user_current);
                $this->em->persist($project);
                $this->em->flush();

                $this->addFlash('success', 'Le projet a bien été créé !');

                return $this->redirectToRoute('app_project_show', ['id' => $project->getId()]);
            }

        }

        return $this->render('project/create.html.twig', [
            'form' => $form->createView()
        ]); 
    }

    #[Route('/{id}', name: 'app_project_show', methods: 'GET')] 
    public function show(Project $project, StepRepository $step_repo): Response
    {
        $steps = $steps = $step_repo->findBy([], ['createdAt' => 'DESC']);
        return $this->render('project/show.html.twig', [
            'project'  => $project,
            'steps' => $steps,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_project_edit', methods: 'GET|POST')] 
    public function edit(Project $project, Request $request): Response
    {
        $form = $this->createForm(ProjectFormType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $this->em->flush();
            $this->addFlash('success', 'Projet modifié !');

            return $this->redirectToRoute('app_user_home');
        }

        return $this->render('project/edit.html.twig', [
            'project'  => $project,
            'form' => $form->createView(),
        ]);
    }

    #[Route("/{id}", name: 'app_project_delete', methods: 'DELETE')]
    public function delete(Project $project, Request $request): Response
    {   
        $user_current = $this->getUser();
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
