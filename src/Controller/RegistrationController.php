<?php

namespace App\Controller;

use App\Entity\PokemonUser;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\courbeNiveau\CourbeXpImpl;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,
                            EntityManagerInterface $entityManager,
                            PokemonRepository $pokemonRepository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLES_USER"]);
            $user->setPiece(50);

            $pokemon = $pokemonRepository->findBy(['nom'=>'Bulbizarre'])[0];

            $pokemonUser = new PokemonUser();
            $pokemonUser->setIdUser($user);
            $pokemonUser->setIdPokemon($pokemon);
            $pokemonUser->setNiveau(1);
            $pokemonUser->setXpMax(CourbeXpImpl::getXpMax($pokemon->getTypeCourbeNiveau(),1));

            $entityManager->persist($pokemonUser);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_default');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
