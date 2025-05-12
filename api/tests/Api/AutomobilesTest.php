<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class AutomobilesTest extends ApiTestCase
{
    public function testCanSeeAutomobiles(): void
    {
        static::createClient()->request('GET', '/automobiles', [

            'headers' => [
                'Content-Type' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);

    }

}
