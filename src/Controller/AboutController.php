<?php

namespace App\Controller;

use App\Entity\About;
use App\Entity\Profile;
use App\Form\AboutType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AboutController extends AbstractController
{
    #[Route("/about", name: "about")]
    public function readAll(ManagerRegistry $doctrine, AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();
        $ProfileRepository = $doctrine->getRepository(Profile::class);
        $profiles = $ProfileRepository->findAll();
        $AboutRepository = $doctrine->getRepository(About::class);
        $abouts = $AboutRepository->findAll();
        return $this->render("about/index.html.twig", ["abouts" =>  $abouts, "profiles" =>  $profiles, "last_username" => $lastUsername, "error" => $error]);
    }

    #[Route('/about/create', name: 'about_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $about = new About();
        dump($about);
        $form = $this->createForm(AboutType::class, $about);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($about);
            $entityManager->flush();
            $this->addFlash("primary", "La formation '{$about->getWork()}' a été créée !");
            return $this->redirectToRoute("about");
        }
        return $this->render("about/form.html.twig", [
            "form" => $form->createView(),
            "type" => "create"
        ]);
    }

    #[Route("/about/edit/{id}", name: "about_edit")]
    function edit(Request $request, ManagerRegistry $doctrine, About $about): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $form = $this->createform(AboutType::class, $about);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash("warning", "La formation '{$about->getWork()}' a été modifiée !");
            $doctrine->getManager()->flush();
            return $this->redirectToRoute("about");
        }
        return $this->render("about/form.html.twig", [
            "form" => $form->createView(),
            "type" => "edit"
        ]);
    }

    #[Route("/about/delete/{id}", name: "about_delete")]
    public function delete(ManagerRegistry $doctrine, About $about): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $this->addFlash("danger", "La formation '{$about->getWork()}' a été supprimée !");
        $entityManager = $doctrine->getManager();
        $entityManager->remove($about);
        $entityManager->flush();
        return $this->redirectToRoute("about");
    }
}
