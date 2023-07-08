<?php

namespace App\Controller;

use App\Entity\Commerce;
use App\Repository\CommerceRepository;
use App\Entity\PokemonUser;
use App\Form\PriceFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Repository\PokemonRepository;
use App\Repository\PokemonUserRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/commerce')]
class CommerceController extends AbstractController
{
    #[Route('/', name: 'app_AccueilCommerce')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants')]
    public function accueil(CommerceRepository $commerceRepository,
                            PokemonUserRepository $pokemonUserRepository,
                            PokemonRepository $pokemonRepository): Response
    {
        $pokemonCommerce = $commerceRepository->findBy(['idUserAcheteur'=>null]);
        for($i=0;$i<count($pokemonCommerce);$i++){
            $completUserPokemon = $pokemonUserRepository->findBy(['id'=>$pokemonCommerce[$i]->getIdPokemonUser()])[0];
            $pokemonInfo = $pokemonRepository->findBy(['id'=>$completUserPokemon->getIdPokemon()])[0];
            $completUserPokemon->setIdPokemon($pokemonInfo);
            $pokemonCommerce[$i]->setIdPokemonUser($completUserPokemon);
            $listIsPokemonUser[] = ($completUserPokemon->getIdUser()->getId() == $this->getUser()->getId());                                  
        }

        return $this->render('commerce/commerce.html.twig', [
            'pokemonsUser' => $pokemonCommerce,
            'isOwnPokemon' => $listIsPokemonUser,
        ]);
    }

    #[Route('/creation', name: 'app_CreationCommerce')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants')]
    public function creationCommerce(PokemonUserRepository $pokemonUserRepository,
                                    PokemonRepository $pokemonRepository,
                                    CommerceRepository $commerceRepository): Response 
    {
        $nbPokeUser = count($pokemonUserRepository->findBy(['idUser'=>$this->getUser()]));
        $pkmUser = $pokemonUserRepository->findBy(['idUser'=>$this->getUser()]);
        //dd($pkmUser);
        //dd($commerceRepository->findBy(['idPokemonUser'=>$pkmUser[2]]));
//Y-M-D
        for($i=0;$i<$nbPokeUser;$i++){
            $pokeUser = $pkmUser[$i];
            $pokemonId = $pokeUser->getIdPokemon();
            $pokemon = $pokemonRepository->findBy(['id'=>$pokemonId]);
            $pokeUser->setIdPokemon($pokemon[0]);
            $listIsVente[] = (!empty($commerceRepository->findBy(['idPokemonUser'=>$pokeUser,'idUserAcheteur'=>null])))
                                ? false
                                : true;
        }

        return $this->render('commerce/creerCommerce.html.twig',[
            'pokemonsUser' => $pkmUser,
            'isVente' => $listIsVente,]);
    }
    #[Route('/creation/pokemon/{id}', name: 'app_creationVente')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants')]
    public function infoPokemon(Request $request,
                                PokemonUser $pokemonUser,
                                PokemonRepository $pokemonRepository,
                                CommerceRepository $commerceRepository,
                                EntityManagerInterface $em) : Response
    {    
        $isCurrentUser = $pokemonUser->getIdUser()->getId() == $this->getUser()->getId();
        if(!$isCurrentUser){
            throw new AccessDeniedException();
        }
        $isVente = (empty($commerceRepository->findBy(['idPokemonUser'=>$pokemonUser,'idUserAcheteur'=>null])))
                                                    ? true
                                                    : false;
   
        $pokemon = $pokemonRepository->findBy(['id'=>$pokemonUser->getIdPokemon()])[0]; 

        $form = $this->createForm(PriceFormType::class);
      
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commerce = new Commerce();
            $montant = $form->get('Montant')->getData();
            
            $commerce->setPrice(intval($montant));
            $commerce->setIdPokemonUser($pokemonUser);

            $em->persist($commerce);
            $em->flush();
            $this->addFlash('success', 'Votre pokémon est en vente !');
            return $this->redirectToRoute('app_AccueilCommerce');
        }
        return $this->render('commerce/fichePokemonCommerce.html.twig', [
            'PriceForm' => $form->createView(),
            'pokemon' => $pokemon,
            'idPokemonUser' =>$pokemonUser->getId(), 
            'isVente' =>$isVente,
        ]);
    }
    #[Route('/retirer', name: 'app_RetirerCommerce')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants')]
    public function retirerInterface(PokemonUserRepository $pokemonUserRepository,
                            PokemonRepository $pokemonRepository,
                            CommerceRepository $commerceRepository)
    {

        $pokemonCommerce = $commerceRepository->findBy(['idUserAcheteur'=>null]);
        for($i=0;$i<count($pokemonCommerce);$i++){
            $completUserPokemon = $pokemonUserRepository->findBy(['id'=>$pokemonCommerce[$i]->getIdPokemonUser()])[0];
            $pokemonInfo = $pokemonRepository->findBy(['id'=>$completUserPokemon->getIdPokemon()])[0];
            $completUserPokemon->setIdPokemon($pokemonInfo);
            $pokemonCommerce[$i]->setIdPokemonUser($completUserPokemon);
        }

        return $this->render('commerce/retirerCommerce.html.twig',[
            'pokemonsUser' => $pokemonCommerce,
            ]);
    }
    #[Route('/retirer/{id}', name: 'app_RetirerVenteId')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants')]
    public function retirerVente(Commerce $commerce,
                                CommerceRepository $commerceRepository,
                                EntityManagerInterface $em): Response
    {
        $commerceRepository->remove($commerce);
        $em->flush();
        $this->addFlash('success', 'Votre pokémon a bien été retiré !');
        return $this->redirectToRoute('app_AccueilCommerce');
    }

    #[Route('/achat/{id}', name: 'app_achatCommerce')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants')]
    public function achat(Commerce $commerce,
                           PokemonUserRepository $pokemonUserRepository,
                           CommerceRepository $commerceRepository,
                           UserRepository $userRepository,
                           EntityManagerInterface $em): Response
    {   
        if(!empty($commerce->getIdUserAcheteur())){
            throw new AccessDeniedException();
        }
        //first we need to verify if user can buy or not 
        $piecesUser = $this->getUser()->getPiece();
        $pricePokemon = $commerce->getPrice();
        if($piecesUser<$pricePokemon){
            $this->addFlash('fail','Pas assez de pièce(s) !');
            return $this->redirectToRoute('app_AccueilCommerce');
        }
        //diff coins 
        $user = $this->getUser();
        $user->setPiece($piecesUser-$pricePokemon);
        $em->persist($user);
         //add to pokemonUser 
        $pokemonUserId = $commerce->getIdPokemonUser();
        //user who sell received coin
        $userSeller = $userRepository->findBy(['id'=>$pokemonUserRepository->findBy(['id'=>$pokemonUserId])[0]->getIdUser()])[0];
        $userSeller->setPiece($userSeller->getPiece()+$pricePokemon);
        $em->persist($userSeller);
        //add to pokemonUser
        $pokemonUserAdd = $pokemonUserRepository->findBy(['id'=>$pokemonUserId])[0];
        $pokemonUserAdd->setIdUser($user);
        $pokemonUserRepository->remove($pokemonUserRepository->findBy(['id'=>$pokemonUserId])[0]);
        $em->persist($pokemonUserAdd);

        //add historic in commerce 
        $commerce->setIdUserAcheteur($user);
        //date
        date_default_timezone_set('Europe/Paris');
        $currentTime = new \DateTime();  
        $commerce->setDateAcheter($currentTime->format('Y-m-d H:i:s'));
        $em->persist($commerce);
        
        //flush
        $em->flush();

        $this->addFlash('success','Achat réussi !');
        return $this->redirectToRoute('app_AccueilCommerce');
    }
    #[Route('/creation/pokemon/{id}/vente', name: 'app_Vente')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants')]
    public function vente(CommerceRepository $commerceRepository,
                          PokemonUser $pokemonUser) : Response
    {

        dd("12");
    }
}
