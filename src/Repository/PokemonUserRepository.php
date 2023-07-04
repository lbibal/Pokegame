<?php

namespace App\Repository;

use App\Entity\PokemonUser;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PokemonUser>
 *
 * @method PokemonUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonUser[]    findAll()
 * @method PokemonUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonUserRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonUser::class);
    }

    public function save(PokemonUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PokemonUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getListIdPokemon(int $idUser)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('distinct p.id AS idPokemon')
            ->from('App\Entity\Pokemon', 'p')
            ->innerJoin('p.pokemonUsers', 'po')
            ->where('po.idUser = :trainerId')
            ->setParameter('trainerId', $idUser);
        $query = $queryBuilder->getQuery();
        $result = $query->getScalarResult();

        return $result;
    }
    public function getNbEvo(int $idUser): int
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('COUNT(p.id) AS evolutionCount')
            ->from('App\Entity\Pokemon', 'p')
            ->innerJoin('p.pokemonUsers', 'po')
            ->where('po.idUser = :trainerId')
            ->andWhere('p.evolution = :const')
            ->setParameter('trainerId', $idUser)
            ->setParameter('const',1);
        $query = $queryBuilder->getQuery();
        $result = $query->getSingleScalarResult();

        return (int) $result;
    }
    public function getNbStarter($idUser) : int
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $queryBuilder->select('COUNT(p.id) AS starterCount')
            ->from('App\Entity\Pokemon', 'p')
            ->innerJoin('p.pokemonUsers', 'po')
            ->where('po.idUser = :trainerId')
            ->andWhere('p.starter = :const')
            ->setParameter('trainerId', $idUser)
            ->setParameter('const',1);
        $query = $queryBuilder->getQuery();
        $result = $query->getSingleScalarResult();

        return (int) $result;
    }
//    /**
//     * @return PokemonUser[] Returns an array of PokemonUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PokemonUser
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
