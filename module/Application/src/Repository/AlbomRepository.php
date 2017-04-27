<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Albom;
/**
 * This is the custom repository class for Post entity.
 */
class AlbomRepository extends EntityRepository
{
    /**
     * Retrieves all alboms in descending order.
     * @return Query
     */
    public function findAlboms()
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('p')
            ->from(Albom::class, 'p')
            ->orderBy('p.priority', 'DESC')
            ->orderBy('p.id', 'DESC');
        
        return $queryBuilder->getQuery();
    }
}