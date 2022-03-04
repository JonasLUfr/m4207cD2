<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function confirmation(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    {
		$nom = $request->request->get("nom");
        $password = $request->request->get("password");
        $vs = $session -> get("nomsession");
        $utilisateur = $manager -> getRepository(Utilisateur::class) -> findOneBy([ 'Login' => $nom ]);
        //$utilisateur = $manager -> getRepository(Utilisateur::class)-> findOneById($userId);
        if($utilisateur == NULL){
            $txt = "Non valide!Vous n'avez pas le droit entre dans cette Page!!";
        }
        else{
            if($utilisateur -> getPassword() == $password){
                if($utilisateur->getId() == 1){
                    $txt = "Good Password Welcome Admin";
                    $val=44;
                    $session -> set("nomsession",$val);
                    $utilisateur->getId();
                }
                else{
                    $txt = "Good Password! Welcome user";
                    $val=44;
                    $session -> set("nomsession",$val);
                    $session->clear();
                }
            }
            else{
                $txt = "Bad Password!";
                $session->clear();
            }
        }

        return $this->render('serveur/confirmation.html.twig', [
            'title' => "Comfimation",
            'nom' => $nom,
            'txt' => $txt,
        ]);
    }
    /**
     * @Route("/afficher_inscription", name="afficher_inscription")
     */
    public function afficher_inscription(): Response  //pour éviter valeur rendre est NULL
    {
        return $this->render('serveur/inscription.html.twig');
    }
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request,EntityManagerInterface $manager): Response
    {
		$recupnom = $request->request->get("nom"); //recuperation de valeur saisir
        $recuppassword = $request->request->get("password");
        $nom = new Utilisateur ();  //création d'un nouvel object
        $nom -> setLogin($recupnom);
        $nom -> setPassword($recuppassword);
        $manager -> persist($nom);
        $manager -> flush();
        
        
        return $this->redirectToRoute ('list_inscription');
    }
    /**
     * @Route("/list_inscription", name="list_inscription")
     */
    public function list_inscription(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    {
        $vs = $session -> get("nomsession");
        if($vs == NULL){
            return $this->redirectToRoute ('serveur');
        }
        else{
            $list_inscription = $manager -> getRepository(Utilisateur::class) -> findAll();//recuperation de valeur dans Utilisateur
            //Valeur de retour
            return $this->render('serveur/list.html.twig',[
                'list_inscription' => $list_inscription,
            ]);
        }
     
        
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(Request $request,SessionInterface $session): Response
    {
        $vs = $session -> get("nomsession");
        $session->clear();
        return $this->render('serveur/logout.html.twig', [
            'txt' => 'Vous avez bien quitté la session Merci!!'
        ]);
    }
}