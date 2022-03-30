<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Document;
use App\Entity\Acces;
use App\Entity\Authorisation;
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
                    $userId = $utilisateur->getId();
                    $val=$userId;
                    $session -> set("nomsession",$val);
                    
                }
                else{
                    $txt = "Good Password! Welcome user";
                    $userId = $utilisateur->getId();
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
    /**
    * @Route("/supprimerUtilisateur/{id}",name="supprimer_Utilisateur")
    */
    public function supprimerUtilisateur(EntityManagerInterface $manager,Utilisateur $editutil): Response {
        $manager->remove($editutil);
        $manager->flush();
        // Affiche de nouveau la liste des utilisateurs
        return $this->redirectToRoute ('list_inscription');
    }

    /**
     * @Route("/afficher_upload", name="afficher_upload")
     */
    public function afficher_upload(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response  //pour éviter valeur rendre est NULL
    {
        $vs = $session -> get("nomsession"); //pour éviter les users ne sont pas login
        if($vs == NULL){
            return $this->redirectToRoute ('serveur');
        }
        else{
            $utilisateur = $manager -> getRepository(Utilisateur::class)->findOneById($vs);
            $usernom = $utilisateur->getLogin();  //récupérer le nom dans la sessions succès!
            return $this->render('serveur/upfichier.html.twig',[
                'usernom' => $usernom
            ]);
        }
    }
    /**
    * @Route("/traitementdufichier",name="traitementdufichier")
    */
    public function traitementdufichier(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response 
    {
        /*$recupfichier = $request->request->get("myfile"); //recuperation de valeur saisir*/
        $uploads_dir = '/home/etudrt/RunnianLU/public/uploads';
        $tmp_name = $_FILES["myfile"]["tmp_name"];
        // basename() peut empêcher les attaques de système de fichiers;
        // la validation/assainissement supplémentaire du nom de fichier peut être approprié
        $name = basename($_FILES["myfile"]["name"]);
        if(move_uploaded_file($tmp_name, "$uploads_dir/$name")){
            $txt = "Bien passé! Le fichier que vous avez envoyé a été enregistré par ce site Web!";
            $myfile = new Document ();  //création d'un nouvel object dans entity Document
            $myfile -> setChemin($name);
            $myfile -> setDate(new \DateTime('now'));
            $myfile -> setActif(true);
            $manager -> persist($myfile);
            $manager -> flush();

            $vs = $session -> get("nomsession");
            $utilisateur = $manager -> getRepository(Utilisateur::class)->findOneById($vs);
            /*$Id_utilisateur = $utilisateur->getId();*/
            $document = $manager -> getRepository(Document::class)->findOneBy([ 'Chemin' => $name ]);
            /*$Id_document = $document-> getId();*/
            $auto = $manager -> getRepository(Authorisation::class)->findOneById($vs);
            $document_acces = new Acces ();
            $document_acces -> setUtil($utilisateur);
            $document_acces -> setDoc($document);
            $document_acces -> setAuto($auto);
            $manager -> persist($document_acces);
            $manager -> flush();
        }
        else{
            $txt = "Oops Erreur!";
        }
        return $this->render('serveur/fichierconfirmation.html.twig',[
            'txt' => $txt,
        ]);
        /*return $this->redirectToRoute ('list_inscription');*/
    }
    /**
     * @Route("/list_fichiers", name="list_fichiers")
     */
    public function list_fichiers(Request $request,EntityManagerInterface $manager,SessionInterface $session): Response
    {
        $vs = $session -> get("nomsession");
        if($vs == NULL){
            return $this->redirectToRoute ('serveur');
        }
        else{
            $utilisateur = $manager -> getRepository(Utilisateur::class)->findOneById($vs);//recuperation de valeur dans Utilisateur-id
            $lst_fichiers_util=$manager->getRepository(Acces::class)->findBy(['util' => $utilisateur]); //Document.php private valeur
            $lst_fichiers_util2=$manager->getRepository(Document::class)->findAll(['id' => $lst_fichiers_util=$manager]);
            //Valeur de retour
            return $this->render('serveur/list_fichiers.html.twig',[
                'list_fichiers' => $lst_fichiers_util2,
            ]);
        }
     
        
    }
}
