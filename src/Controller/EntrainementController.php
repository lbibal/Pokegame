<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Entity\PokemonUser;
use App\Repository\PokemonRepository;
use App\Repository\PokemonUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Service\courbeNiveau\CourbeXpImpl;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/entrainement')]
class EntrainementController extends AbstractController
{
    private $currentTime;
    public function __construct()
    {
        date_default_timezone_set('Europe/Paris');
        $this->currentTime = new \DateTime();  
    }

    #[Route('/', name: 'app_entrainement')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants')]
    public function index(PokemonUserRepository $pokemonUserRepository,
                        PokemonRepository $pokemonRepository): Response
    {
        $nbPokeUser = count($pokemonUserRepository->findBy(['idUser'=>$this->getUser()]));
        $pkmUser = $pokemonUserRepository->findBy(['idUser'=>$this->getUser()]);
   
//Y-M-D
        for($i=0;$i<$nbPokeUser;$i++){
            $pokeUser = $pkmUser[$i];
            $pokemonId = $pokeUser->getIdPokemon();
            $pokemon = $pokemonRepository->findBy(['id'=>$pokemonId]);
            $pokeUser->setIdPokemon($pokemon[0]);
            $listIsTraining[] = (!empty($pokeUser->getLastTrainingTime()))
                                ? date_create($pokeUser->getLastTrainingTime())<$this->currentTime
                                : true;
        }
        
        return $this->render('entrainement/entrainement.html.twig', [
            'controller_name' => 'EntrainementController',
            'pokemonsUser' => $pkmUser,
            'isTraining' => $listIsTraining,
        ]);
    }

    #[Route('/pokemons/{id}', name: 'app_fichePokemons')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants')]
    public function infoPokemon(PokemonUser $pokemonUser,
                                PokemonRepository $pokemonRepository) : Response
    {    
        $isCurrentUser = $pokemonUser->getIdUser()->getId() == $this->getUser()->getId();
        if(!$isCurrentUser){
            throw new AccessDeniedException();
        }

        $isTraining = (empty($pokemonUser->getLastTrainingTime()))
                                ? true
                                : date_create($pokemonUser->getLastTrainingTime())<$this->currentTime;
   
        $pokemon = $pokemonRepository->findBy(['id'=>$pokemonUser->getIdPokemon()])[0]; 
        return $this->render('entrainement/fichePokemon.html.twig', [
            'pokemon' => $pokemon,
            'idPokemonUser' =>$pokemonUser->getId(), 
            'isTraining' =>$isTraining,
        ]);
    }
//maybe put in the same route => use issubmitted etc .. 
    #[Route('/pokemons/{id}/entrainer', name: 'app_entrainementPokemons')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants')]
    public function entrainer(PokemonUser $pokemonUser,
                            EntityManagerInterface $entityManager,
                            PokemonRepository $pokemonRepository) : Response
    {
        
        //search pokemon letter
        $pokemonCourbeLetter = $pokemonRepository->findBy(['id'=>$pokemonUser->getIdPokemon()])[0]
                                                 ->getTypeCourbeNiveau();
           
        //mark lastTrainingTime
        date_default_timezone_set('Europe/Paris');
        $currentTime = new \DateTime();  
        
        //random xp
        $xp = rand(10, 30);

        //total xp Gain
        $totalXp = $pokemonUser->getXpGain() + $xp;

        while($totalXp>=$pokemonUser->getXpMax())
        {
            $totalXp-=$pokemonUser->getXpMax();
            $pokemonUser->setXpMax(CourbeXpImpl::getXpMax($pokemonCourbeLetter,$pokemonUser->getNiveau()+1));
            $pokemonUser->setNiveau($pokemonUser->getNiveau() + 1);
        }
        $pokemonUser->setXpGain($totalXp);
     
        $currentTime->modify('+1 hour');
        $pokemonUser->setLastTrainingTime($currentTime->format('Y-m-d H:i:s'));
  
        $entityManager->persist($pokemonUser);
        $entityManager->flush();
        //CourbeXpImpl::getXpMax(L)
        
        
        return $this->redirectToRoute('app_fichePokemons',['id'=>$pokemonUser->getId()]);
        
    }
}
