<?php

namespace App\Controller;

use App\Form\RegisterType;
use App\Form\LoginType;
use App\Entity\User;
use App\Repository\PokemonUserRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use App\Service\courbeNiveau\CourbeXpImpl;

class DefaultController extends AbstractController
{

    #[Route('/', name: 'app_default')]
    public function index(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_accueil');
        }
        return $this->render('presentation.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/accueil',name: 'app_accueil')]
    public function accueil(PokemonUserRepository $pokemonUserRepository): Response 
    {   
    
        $nbPoke = count($pokemonUserRepository->findBy(['idUser'=>$this->getUser()->getId()]));
        $nbEvo = $pokemonUserRepository->getNbEvo($this->getUser()->getId());
        $nbStarter = $pokemonUserRepository->getNbStarter($this->getUser()->getId()); 
        return $this->render('default/accueil.html.twig', [
            'user' => $this->getUser(),
            'nbPokemon' => $nbPoke,
            'nbEvo' => $nbEvo,
            'nbStarter' => $nbStarter,
        ]);
    }

    //method get and post
   /* #[Route('/register',name: 'register')]
    public function register(Request $request,
                            EntityManagerInterface $em,
                            UserPasswordHasherInterface $userPasswordHasher,
                            UserRepository $userRepository){
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

    // Gérer la soumission du formulaire d'inscription et le traitement des données
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $username = $form->get('username')->getData();
        $isUsernameTaken = $userRepository->findBy(['username'=>$username]);
        // Les données du formulaire sont valides, effectuez ici les opérations d'inscription
        if ($isUsernameTaken) {
            
            $this->addFlash('fail', 'Username already taken !');
            return $this->render('register.html.twig', [
                'form' => $form->createView(),
            ]);
            // Rediriger vers la page d'inscription ou afficher le formulaire à nouveau avec le message d'erreur
        }
            $user->setRoles(["ROLES_USER"]);
            $user->setPassword($userPasswordHasher->hashPassword($user,$user->getPassword()));
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
    }

    return $this->render('register.html.twig', [
        'form' => $form->createView(),
    ]);
    }
*/

    /*#[Route('/logout',name: 'logout')]
    public function logout()
    {
        // Cette action ne sera jamais exécutée car la déconnexion est gérée automatiquement par le composant Security
    }*/

    /*#[Route('/login',name: 'login')]
    public function login(Request $request,UserPasswordHasherInterface $userPasswordHasher,
                        UserRepository $userRepository){

        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, effectuez ici les opérations de connexion
            $username = $form->get('username')->getData();
            $inBaseUsername = $userRepository->findBy(['username'=>$username]);
            if(!$inBaseUsername){
                $this->addFlash('fail', 'Username or Password incorrect');
                
            }else{
                $password = $form->get('password')->getData();
            //username is unique by default, it means we cannot get another username in BASE 
                $isPasswordValid = $userPasswordHasher->isPasswordValid($inBaseUsername[0],$password);
                if(!$isPasswordValid){
                    $this->addFlash('fail', 'Username or Password incorrect');
                }else{
                    return $this->render('base.html.twig',[
                        'form' => $form->createView(),
                    ]);  
                }
            }  
        }
        return $this->render('login.html.twig',[
            'form' => $form->createView(),
        ]);
    }*/
}
