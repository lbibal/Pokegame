<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use App\Repository\PokemonUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/entrainement')]
class EntrainementController extends AbstractController
{
    #[Route('/', name: 'app_entrainement')]
    public function index(PokemonUserRepository $pokemonUserRepository,
                        PokemonRepository $pokemonRepository): Response
    {
        $nbPokeUser = count($pokemonUserRepository->findBy(['idUser'=>$this->getUser()]));
        $pkmUser = $pokemonUserRepository->findBy(['idUser'=>$this->getUser()]);

        $currentTime = new \DateTime();
        $target = date_create();
        $target2 = date_create('2009-10-13 10:00:00');
        
        $interval = date_diff($target2, $target);
        //dd();
        

        for($i=0;$i<$nbPokeUser;$i++){
            $pokeUser = $pkmUser[$i];
            $pokemonId = $pokeUser->getIdPokemon();
            $pokemon = $pokemonRepository->findBy(['id'=>$pokemonId]);
            $pokeUser->setIdPokemon($pokemon[0]);
            $listIsTraining[] = $pokeUser->getLastTrainingTime()<$currentTime;
        }
        //dd($listIsTraining);
        return $this->render('entrainement/entrainement.html.twig', [
            'controller_name' => 'EntrainementController',
            'pokemonsUser' => $pkmUser,
            'isTraining' => $listIsTraining,
        ]);
    }
    
    //#[Route('/pokemons/{id}', name: 'app_entrainementPokemons')]
    //#[Route('/pokemons/{id}/entrainer', name: 'app_entrainementPokemons')]
    
}
