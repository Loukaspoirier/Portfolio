<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Entity\Skill;
use App\Form\SkillType;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class TechStackController extends AbstractController
{
    #[Route('/tech_stack', name: 'tech_stack')]
    public function index(ManagerRegistry $doctrine, AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();
        $ProfileRepository = $doctrine->getRepository(Profile::class);
        $profiles = $ProfileRepository->findAll();
        $SkillRepository = $doctrine->getRepository(Skill::class);
        $skills = $SkillRepository->findAll();
        return $this->render("tech_stack/index.html.twig", ["skills" =>  $skills , "profiles" =>  $profiles, "last_username" => $lastUsername, "error" => $error]);
    }

    #[Route('/tech_stack/create', name: 'tech_stack_create')]
    public function create(Request $request, ManagerRegistry $doctrine, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $project = new Skill();
        $form = $this->createForm(SkillType::class, $project);
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
            return $this->redirectToRoute("tech_stack");
        }
        return $this->render("tech_stack/form.html.twig", [
            "form" => $form->createView(),
            "type" => "create"
        ]);
    }
    #[Route("/tech_stack/edit/{id}", name: "tech_stack_edit")]
    function edit(Request $request, ManagerRegistry $doctrine, Skill $skill, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $form = $this->createform(SkillType::class, $skill);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $skill->setBrochureFilename($brochureFileName);
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($skill);
            $entityManager->flush();
            $this->addFlash("warning", "La compétence '{$skill->getName()}' a été modifiée !");
            return $this->redirectToRoute("tech_stack");
        }
        return $this->render("tech_stack/form.html.twig", [
            "form" => $form->createView(),
            "type" => "edit"
        ]);
    }

    #[Route("/tech_stack/delete/{id}", name: "tech_stack_delete")]
    public function delete(ManagerRegistry $doctrine, Skill $skill): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $this->addFlash("danger", "La compétence '{$skill->getName()}' a été supprimée !");
        $entityManager = $doctrine->getManager();
        $entityManager->remove($skill);
        $entityManager->flush();
        return $this->redirectToRoute("tech_stack");
    }
}
