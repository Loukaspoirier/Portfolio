<?php

namespace App\Controller;

use App\Entity\Profile;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $doctrine, AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();
        $ProfileRepository = $doctrine->getRepository(Profile::class);
        $profiles = $ProfileRepository->findAll();
        return $this->render("home/index.html.twig", [
            "profiles" =>  $profiles, 'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
}
