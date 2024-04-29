<?php

namespace App\Repository;

use App\Entity\Event;
use App\Model\SearchData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

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
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Event::class);
        $this->paginator = $paginator;
    }

    /**
     * Retrieves search-related events
     * @return PaginatorInterface
     */
    public function findSearch(SearchData $searchData)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e', 'c')
            ->join('e.category', 'c')
            ->innerJoin('e.ambiance_event', 'a')
            ->innerJoin('e.specialRegime_event', 's');

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

        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            1,
            20
        );
    }
}
