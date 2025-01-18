<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Profile;
use App\Service\FileUploader;
use App\Entity\Project;
use App\Form\ProjectType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ProjectController extends AbstractController
{
    #[Route("/project/search", name: "project_search")]
    
    public function search(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $em): Response
    {
        $name = $request->query->get('name');
        $projects = $em->getRepository(Project::class)->findByName($name);
        $ProfileRepository = $doctrine->getRepository(Profile::class);
        $profiles = $ProfileRepository->findAll();
        return $this->render("project/index.html.twig", ['projects' => $projects, "profiles" =>  $profiles]);
    }

    #[Route("/project/tri", name: "project_tri")]

    public function tri(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $em): Response
    {
        $projects = $em->getRepository(Project::class)->findAllSortedByDate();
        $ProfileRepository = $doctrine->getRepository(Profile::class);
        $profiles = $ProfileRepository->findAll();
        return $this->render("project/index.html.twig", ["projects" =>  $projects , "profiles" =>  $profiles]);
    }
    #[Route("/project/reverseTri", name: "project_reverse_tri")]

    public function reverseTri(ManagerRegistry $doctrine, Request $request, EntityManagerInterface $em): Response
    {
        $projects = $em->getRepository(Project::class)->findAllReverseSortedByDate();
        $ProfileRepository = $doctrine->getRepository(Profile::class);
        $profiles = $ProfileRepository->findAll();
        return $this->render("project/index.html.twig", ["projects" =>  $projects , "profiles" =>  $profiles]);
    }

    #[Route("/project", name: "project")]
    public function readAll(ManagerRegistry $doctrine, Request $request, AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();
        $ProfileRepository = $doctrine->getRepository(Profile::class);
        $profiles = $ProfileRepository->findAll();
        $ProjectRepository = $doctrine->getRepository(Project::class);
        $projects = $ProjectRepository->findAll();
        return $this->render("project/index.html.twig", ["projects" =>  $projects , "profiles" =>  $profiles, "last_username" => $lastUsername, "error" => $error]);
    }

    #[Route('/project/create', name: 'create')]
    public function create(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $project->setBrochureFilename($brochureFileName);
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($project);
            $entityManager->flush();
            $this->addFlash("primary", "Le project '{$project->getName()}' a été créé !");
            return $this->redirectToRoute("project");
        }
        return $this->render("project/form.html.twig", [
            "form" => $form->createView(),
            "type" => "create"
        ]);
    }

    #[Route("/project/edit/{id}", name: "edit")]
    function edit(Request $request, ManagerRegistry $doctrine, Project $project, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $form = $this->createform(ProjectType::class, $project);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $project->setBrochureFilename($brochureFileName);
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($project);
            $entityManager->flush();
            $this->addFlash("warning", "Le projet '{$project->getName()}' a été modifié !");
            return $this->redirectToRoute("project");
        }
        return $this->render("project/form.html.twig", [
            "form" => $form->createView(),
            "type" => "edit"
        ]);
    }

    #[Route("/project/delete/{id}", name: "delete")]
    public function delete(ManagerRegistry $doctrine, Project $project): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $this->addFlash("danger", "Le projet '{$project->getName()}' a été supprimé !");
        $entityManager = $doctrine->getManager();
        $entityManager->remove($project);
        $entityManager->flush();
        return $this->redirectToRoute("project");
    }
}
