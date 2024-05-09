<?php

namespace App\Repository;

use App\Entity\Event;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Retrieves search-related events
     * @return Event[]
     */
    public function findSearch(SearchData $searchData)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e', 'c')
            ->join('e.category', 'c')
            ->innerJoin('e.ambiance_event', 'a')
            ->innerJoin('e.specialRegime_event', 's');

        // if (!empty($searchData->latitude) && !empty($searchData->longitude)) {
        //     $query = $query
        //         ->andWhere('e.latitude = :latitude')
        //         ->andWhere('e.longitude = :longitude')
        //         ->setParameter('latitude', $searchData->latitude)
        //         ->setParameter('longitude', $searchData->longitude);
        // }

        if (!empty($searchData->price)) {
            $query = $query
                ->andWhere('e.price IN (:price)')
                ->setParameter('price', $searchData->price);
        }

        if (!empty($searchData->category)) {
            $query = $query
                ->andWhere('c.id IN (:category)')
                ->setParameter('category', $searchData->category);
        }

        if (!empty($searchData->open_hours)) {
            $query = $query
                ->andWhere('e.open_hours >= :open_hours')
                ->setParameter('open_hours', $searchData->open_hours);
        }

        if (!empty($searchData->close_hours)) {
            $query = $query
                ->andWhere('e.close_hours <= :close_hours')
                ->setParameter('close_hours', $searchData->close_hours);
        }

        if (!empty($searchData->ambiance_event)) {
            $query = $query
                ->andWhere('a.id IN (:ambiance)')
                ->setParameter('ambiance', $searchData->ambiance_event);
        }

        if (!empty($searchData->specialRegime_event)) {
            $query = $query
                ->andWhere('s.id IN (:specialRegime)')
                ->setParameter('specialRegime', $searchData->specialRegime_event);
        }

        return $query->getQuery()->getResult();
    }
}
