<?php
namespace App\Repository;

use App\Entity\Brand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brand::class);
    }

    /**
     * Find all brands that manufacture a specific vehicle type
     *
     * @param string $vehicleType
     * @return array
     */
    public function findBrandsByVehicleType(string $vehicleType): array
    {
        return $this->createQueryBuilder('b')
            ->select('DISTINCT b')
            ->innerJoin('b.automobiles', 'a')
            ->where('a.name LIKE :vehicleType OR a.description LIKE :vehicleType')
            ->setParameter('vehicleType', '%' . $vehicleType . '%')
            ->getQuery()
            ->getResult();
    }
}
