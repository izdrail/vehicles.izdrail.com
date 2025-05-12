<?php
// src/Service/Handler/AutomobileHandler.php
namespace App\Handlers;

use App\Entity\Automobile;
use Doctrine\ORM\EntityManagerInterface;


class AutomobileHandler extends BaseHandler
{
    public function __invoke(array $data, EntityManagerInterface $entityManager): ?Automobile
    {
        // Find the corresponding brand
        $brand = $entityManager->getRepository(\App\Entity\Brand::class)
            ->findOneBy(['id' => $data['brand_id'] ]);

        $automobile = new Automobile();

        $automobile->setUrlHash($data['url_hash']);
        $automobile->setUrl($data['url']);
        $automobile->setBrand($brand);
        $automobile->setName($data['name']);
        $automobile->setDescription($data['description'] ?? null);
        $automobile->setPhotos(is_string($data['engine_name'] ?? '[]') ? $data['engine_name'] : json_encode($data['engine_name']));


        $automobile->setCreatedAt($this->parseDate($data['created_at'] ?? null));
        $automobile->setUpdatedAt($this->parseDate($data['updated_at'] ?? null));

        $entityManager->persist($automobile);
        $entityManager->flush();

        return $automobile;
    }
}
