<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

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
    public function confirmation(Request $request,EntityManagerInterface $manager): Response
    {
		$nom = $request->request->get("nom");
        $password = $request->request->get("password");

        $utilisateur = $manager -> getRepository(Utilisateur::class) -> findOneBy([ ’Utilisateur’ => ‘nom’]);)
        
        if($nom == "admin"){
            $txt = "valide!";
        }
        else{
            $txt = "Non valide!Vous n'avez pas le droit entre dans cette Page!!";
        }

        return $this->render('serveur/confirmation.html.twig', [
            'title' => "Confirmation",
            'nom' => $nom,
            'txt' => $txt,
        ]);
    }
}
