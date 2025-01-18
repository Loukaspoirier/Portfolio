<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use App\Entity\Profile;
use App\Form\ProfileType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProfileController extends AbstractController
{

    #[Route("/profile/{id}", name: "profile_edit")]
    function edit(Request $request, ManagerRegistry $doctrine, Profile $profile, FileUploader $fileUploader): Response
    {
        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $form = $this->createform(ProfileType::class, $profile);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();
            if ($brochureFile) {
                $brochureFileName = $fileUploader->upload($brochureFile);
                $profile->setBrochureFilename($brochureFileName);
            }
            $entityManager = $doctrine->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();
            $this->addFlash("warning", "La photo de profile a été modifiée !");
            return $this->redirectToRoute("home");
        }
        return $this->render("profile/form.html.twig", [
            "form" => $form->createView()
        ]);
    }
}
