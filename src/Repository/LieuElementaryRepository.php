<?php

namespace App\Repository;

use App\Entity\LieuElementary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LieuElementary>
 *
 * @method LieuElementary|null find($id, $lockMode = null, $lockVersion = null)
 * @method LieuElementary|null findOneBy(array $criteria, array $orderBy = null)
 * @method LieuElementary[]    findAll()
 * @method LieuElementary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LieuElementaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LieuElementary::class);
    }

    public function save(LieuElementary $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LieuElementary $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findPokemonByLieuAndType2($idLieu){
        return $this->createQueryBuilder('le')
        ->select('distinct p1')
        ->from('App\Entity\Elementary','e')
        ->from('App\Entity\Pokemon','p1')
        ->innerJoin('e.lieuElementaries','a')
        ->innerJoin('e.pokemon2','p')
        ->where('le.idLieu=:lieu')
        ->andWhere('a.idElementary = le.idElementary')
        ->andWhere('p.type2 = p1.type2')
        ->setParameter('lieu',$idLieu)
        ->getQuery()
        ->getResult();
    }
    public function findPokemonByLieuAndType1($idLieu){
        return $this->createQueryBuilder('le')
            ->select('distinct p1')
            ->from('App\Entity\Elementary','e')
            ->from('App\Entity\Pokemon','p1')
            ->innerJoin('e.lieuElementaries','a')
            ->innerJoin('e.pokemon','p')
            ->where('le.idLieu=:lieu')
            ->andWhere('a.idElementary = le.idElementary')
            ->andWhere('p.type1 = p1.type1')
            ->setParameter('lieu',$idLieu)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return LieuElementary[] Returns an array of LieuElementary objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LieuElementary
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
