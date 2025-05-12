<?php
// src/Service/Handler/EngineHandler.php
namespace App\Handlers;

use App\Entity\Engine;
use Doctrine\ORM\EntityManagerInterface;

class EngineHandler extends BaseHandler
{
    public function __invoke(array $data, EntityManagerInterface $entityManager): ?Engine
    {

        // Find the corresponding automobile
        $automobile = $entityManager->getRepository(\App\Entity\Automobile::class)
            ->findOneBy(['id' => $data['automobile_id'] ?? '']);

        if (!$automobile) {
            return null; // Skip if automobile not found
        }

        // Validate required fields (adjust based on Engine entity)
        if (empty($data['name'])) {
            return null;
        }

        $engine = new Engine();
        $engine->setOtherId((int)($data['other_id'] ?? 0));
        $engine->setAutomobile($automobile); // Use setAutomobile instead of setAutomobileId
        $engine->setName($data['name']);
        $engine->setSpecs(is_string($data['specs'] ?? '{}') ? $data['specs'] : json_encode($data['specs']));
        $engine->setCreatedAt($this->parseDate($data['created_at'] ?? null));
        $engine->setUpdatedAt($this->parseDate($data['updated_at'] ?? null));

        return $engine;
    }
}
