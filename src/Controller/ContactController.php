<?php

namespace App\Controller;

use App\Entity\Profile;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(ManagerRegistry $doctrine, AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();
        $ProfileRepository = $doctrine->getRepository(Profile::class);
        $profiles = $ProfileRepository->findAll();
        return $this->render("contact/index.html.twig", [
            "profiles" =>  $profiles, 'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}
