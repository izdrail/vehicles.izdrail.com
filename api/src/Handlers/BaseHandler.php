<?php

namespace App\Handlers;

use App\Entity\Brand;
use Doctrine\ORM\EntityManagerInterface;

class BaseHandler
{
    public function parseDate(?string $dateString): ?\DateTime
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            return new \DateTime($dateString);
        } catch (\Exception $e) {
            // Invalid date format, return null
            return null;
        }
    }
}
