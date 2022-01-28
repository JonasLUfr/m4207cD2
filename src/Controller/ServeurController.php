<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServeurController extends AbstractController
{
    /**
     * @Route("/serveur", name="serveur")
     */
    public function index(): Response
    {
        return $this->render('serveur/index.html.twig', [
            'controller_name' => 'ServeurController',
        ]);
    }
    /**
     * @Route("/confirmation", name="confirmation")
     */
    public function confirmation(Request $request): Response
    {
        $nom = $request->request->get("nom");
		$email = $request->request->get("email");
        return $this->render('serveur/confirmation.html.twig', [
            'title' => "Confirmation",
			'nom' => "$nom",
			'email' => "$email",
        ]);
    }
}
