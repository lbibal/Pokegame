<?php

namespace App\Controller;

use App\Repository\PokemonRepository;
use App\Repository\PokemonUserRepository;
use App\Entity\Pokemon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PokemonController extends AbstractController
{
    #[Route('/listPokemon', name: 'app_listPokemon')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants')]
    public function getListPokemon(PokemonUserRepository $pokemonUserRepository,
                                    PokemonRepository $pokemonRepository): Response
    {
        $nbPokeUser = count($pokemonUserRepository->findBy(['idUser'=>$this->getUser()]));
        $pkmUser = $pokemonUserRepository->findBy(['idUser'=>$this->getUser()]);
        for($i=0;$i<$nbPokeUser;$i++){
            $pokeUser = $pkmUser[$i];
            $pokemonId = $pokeUser->getIdPokemon();
            $pokemon = $pokemonRepository->findBy(['id'=>$pokemonId]);
            $pokeUser->setIdPokemon($pokemon[0]);
        }
        return $this->render('pokemon/index.html.twig', [
            'pokemonsUser' => $pkmUser,
        ]);
    }

    #[Route('/pokemon/{id}', name: 'app_PokemonInfo')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants')]
    public function getInfoPokemon(Pokemon $pokemon) : Response 
    {
        return $this->render('pokemon/pokemonInfo.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }
}
