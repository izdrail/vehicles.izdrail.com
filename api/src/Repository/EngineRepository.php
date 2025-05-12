<?php

namespace App\Repository;

use App\Entity\Engine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EngineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Engine::class);
    }

    /**
     * Find engine with full details
     *
     * @param int $id
     * @return Engine|null
     */
    public function findWithDetails(int $id): ?Engine
    {
        return $this->createQueryBuilder('e')
            ->select('e', 'a')
            ->leftJoin('e.automobile', 'a')
            ->where('e.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
