<?php

namespace App\Repository;

use App\Entity\SpecialRegime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SpecialRegime>
 *
 * @method SpecialRegime|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpecialRegime|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpecialRegime[]    findAll()
 * @method SpecialRegime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialRegimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpecialRegime::class);
    }

    //    /**
    //     * @return SpecialRegime[] Returns an array of SpecialRegime objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SpecialRegime
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
