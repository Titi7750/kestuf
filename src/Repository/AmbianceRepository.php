<?php

namespace App\Repository;

use App\Entity\Ambiance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ambiance>
 *
 * @method Ambiance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ambiance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ambiance[]    findAll()
 * @method Ambiance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmbianceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ambiance::class);
    }

    //    /**
    //     * @return Ambiance[] Returns an array of Ambiance objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ambiance
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
