<?php

namespace App\Handlers;

use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;


class BrandHandler extends BaseHandler
{
    public function __invoke(array $data, EntityManagerInterface $entityManager): ?Brand
    {
        // Validate required fields
        if (empty($data['url_hash']) || empty($data['url']) || empty($data['name'])) {
            return null; // Skip invalid rows
        }

        $brand = new Brand();
        $brand->setUrlHash($data['url_hash']);
        $brand->setUrl($data['url']);
        $brand->setName($data['name']);
        $brand->setLogo($data['logo'] ?? null);

        // Handle created_at
        $brand->setCreatedAt($this->parseDate($data['created_at'] ?? null));

        // Handle updated_at
        $brand->setUpdatedAt($this->parseDate($data['updated_at'] ?? null));

        return $brand;
    }

}
