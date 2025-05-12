<?php
namespace App\Repository;

use App\Entity\Automobile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AutomobileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Automobile::class);
    }

    /**
     * Find automobiles by vehicle type
     *
     * @param string $vehicleType
     * @return array
     */
    public function findByVehicleType(string $vehicleType): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.name LIKE :vehicleType OR a.description LIKE :vehicleType')
            ->setParameter('vehicleType', '%' . $vehicleType . '%')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find automobile with all technical details
     *
     * @param int $id
     * @return Automobile|null
     */
    public function findWithTechnicalDetails(int $id): ?Automobile
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'b', 'e')
            ->leftJoin('a.brand', 'b')
            ->leftJoin('a.engines', 'e')
            ->where('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
