<?php

namespace App\Controller;

use App\Entity\PokemonUser;
use App\Form\CaptureFormType;
use App\Repository\LieuElementaryRepository;
use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PokemonRepository;
use App\Repository\PokemonUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\courbeNiveau\CourbeXpImpl;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/capture')]
class CaptureController extends AbstractController
{
    private $currentTime;
    public function __construct()
    {
        date_default_timezone_set('Europe/Paris');
        $this->currentTime = new \DateTime();  
    }

    #[Route('/', name: 'app_AccueilCapture')]
    public function index(PokemonUserRepository $pokemonUserRepository,
                        PokemonRepository $pokemonRepository): Response
    {
        $listIsTraining = array();
        $listIsCapture = array();
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
            $listIsCapture[] = (!empty($pokeUser->getLastCaptureTime()))
                                            ? date_create($pokeUser->getLastCaptureTime())<$this->currentTime
                                            : true;
        }
        //dd($this->currentTime);
        return $this->render('capture/capture.html.twig', [
            'controller_name' => 'CaptureController',
            'pokemonsUser' => $pkmUser,
            'isTraining' => $listIsTraining,
            'isCapture' => $listIsCapture,
        ]);
    }
    #[Route('/{id}/lieu', name: 'app_PokemonCapture')]
    public function capture(PokemonUser $pokemonUser,
                            Request $request,
                            LieuElementaryRepository $lieuElementaryRepository,
                            LieuRepository $lieuRepository,
                            EntityManagerInterface $entityManager): Response
    {
        $isTraining = (empty($pokemonUser->getLastTrainingTime()))
                                ? true
                                : date_create($pokemonUser->getLastTrainingTime())<$this->currentTime;
        $isCapture = (!empty($pokemonUser->getLastCaptureTime()))
                                ? date_create($pokemonUser->getLastCaptureTime())<$this->currentTime
                                : true;

        $form = $this->createForm(CaptureFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lieuData = $form->getData()['lieu'];
            $findLieu = $lieuRepository->findBy(['nom'=>$lieuData])[0];
            
            //get pokemon for a lieu, drop rate x2 if type1 and type2 are include in the lieu  
            $pokemonType2 = $lieuElementaryRepository->findPokemonByLieuAndType2($findLieu);
            $pokemonType1 = $lieuElementaryRepository->findPokemonByLieuAndType1($findLieu);

            $listFullPokemon = array_merge($pokemonType1,$pokemonType2);

            $randomIndex = array_rand($listFullPokemon);
            $pokemonCaptured = $listFullPokemon[$randomIndex];

            //init time 
            date_default_timezone_set('Europe/Paris');
            $currentTime = new \DateTime();  
            //init pokemonUser
            $currentTime->modify('+1 hour');
            $pokemonUserInit = new PokemonUser();

            $pokemonUserInit->setIdUser($this->getUser());
            $pokemonUserInit->setXpMax(CourbeXpImpl::getXpMax($pokemonCaptured->getTypeCourbeNiveau(),1));
            $pokemonUserInit->setNiveau(1);
            $pokemonUserInit->setIdPokemon($pokemonCaptured);
            $pokemonUserInit->setLastCaptureTime($currentTime->format('Y-m-d H:i:s'));

            $pokemonUser->setLastCaptureTime($currentTime->format('Y-m-d H:i:s'));

            $entityManager->persist($pokemonUser);
            $entityManager->persist($pokemonUserInit);
            $entityManager->flush();

            $this->addFlash('success','Vous avez attraper un '.$pokemonCaptured->getNom().' bravo! ');
            return $this->redirectToRoute('app_AccueilCapture');
        }
        return $this->render('capture/lieu.html.twig', [
            'CaptureForm' => $form->createView(),
            'isTraining' => $isTraining,
            'isCapture' => $isCapture,
        ]);
    }
}
