<?php

namespace App\DataFixtures;

use App\Entity\Elementary;
use App\Entity\Pokemon;
use App\Entity\PokemonUser;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PokemonFixtures extends Fixture
{
    private $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        //create a user
        $user = new User();
        $user->setUsername("Lucas");
        $user->setPseudo("Tom");
        $user->setRoles(["ROLES_USER"]);
        $user->setEmail("aze@gmail.com");
        $user->setPassword($this->userPasswordHasher->hashPassword($user,"password"));
        $user->setPiece(0);
        $manager->persist($user);

        //create elementary
        $listElem = array("ACIER","COMBAT","DRAGON","EAU","ELECTRIK","FEU","GLACE","INSECTE","NORMAL",
                        "PLANTE","POISON","PSY","ROCHE","SOL","SPECTRE","VOL","FEE");
        for($i=0;$i<count($listElem);$i++){
            $elem = new Elementary();
            $elem->setLibelle($listElem[$i]);
            $manager->persist($elem);
            $listElemObject[] = $elem; 
        }

        //create pokemon
        $listNom = array('Bulbizarre','Herbizarre','Florizarre','Salamèche','Reptincel',
                        'Dracaufeu','Carapuce','Carabaffe','Tortank','Chenipan','Chrysacier',
                        'Papilusion','Aspicot','Coconfort','Dardargnan','Roucool','Roucoups',
                        'Roucarnage','Rattata','Rattatac','Piafabec','Rapasdepic','Abo','Arbok',
                        'Pikachu');

        $listEvolution = array(false,true,true,false,true,true,false,true,
                               true,false,true,true,false,true,true,false,
                                true,true,false,true,false,true,false,true,false);

        $listStarter = array(true,false,false,true,false,false,true,false,
                            false,false,false,false,false,false,false,false,false,false,false,
                            false,false,false,false,false,false);

        $listTypeCourbEvolution = array('P','P','P','P','P','P','P','P','P','M','M','M',
                                        'M','M','M','M','P','P','P','M','M','M','M','M','M','M');

        $listType1 = array($listElemObject[9],$listElemObject[9],$listElemObject[9],
                           $listElemObject[5],$listElemObject[5],$listElemObject[5]
                           ,$listElemObject[3],$listElemObject[3],$listElemObject[3],
                           $listElemObject[7],$listElemObject[7],$listElemObject[7]
                           ,$listElemObject[7],$listElemObject[7],$listElemObject[7]
                           ,$listElemObject[8],$listElemObject[8],$listElemObject[8]
                           ,$listElemObject[8],$listElemObject[8],$listElemObject[8]
                           ,$listElemObject[8],$listElemObject[10],$listElemObject[10]
                           ,$listElemObject[4]);

        $listType2 = array($listElemObject[10],$listElemObject[10],$listElemObject[10],null
                            ,null,$listElemObject[15],null,null,null,null,null
                            ,$listElemObject[15],$listElemObject[10],$listElemObject[10]
                            ,$listElemObject[10],$listElemObject[15],$listElemObject[15]
                            ,$listElemObject[15],null,null,$listElemObject[15]
                            ,$listElemObject[15],null,null,null);

        for($i=0;$i<count($listNom);$i++){
            $pokemon = new Pokemon();
            $pokemon->setNom($listNom[$i]);
            $pokemon->setEvolution($listEvolution[$i]);
            $pokemon->setStarter($listStarter[$i]);
            $pokemon->setTypeCourbeNiveau($listTypeCourbEvolution[$i]);
            $pokemon->setType1($listType1[$i]);
            $pokemon->setType2($listType2[$i]);
            if($i == 0){
                $pokemonStarter = $pokemon;
            }
            $manager->persist($pokemon);
        }
        
        //pokemon by default
        $pokemonUser = new PokemonUser();
        $pokemonUser->setIdUser($user);
        $pokemonUser->setIdPokemon($pokemonStarter);
        $pokemonUser->setXpGain(0);
        $pokemonUser->setXpMax(0);
        $pokemonUser->setNiveau(1);
        $manager->persist($pokemonUser);
        
        $manager->flush();
    }
}
