<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class BrandsTest extends ApiTestCase
{
    public function testGetBrands(): void
    {
        static::createClient()->request('GET', '/brands', [
            'headers' => [
                'Accept' => 'application/ld+json',
            ],
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains(['@context' => '/contexts/Brand']);
    }

    public function testCreateBrand(): void
    {
        $client = static::createClient();

        $brandData = [
            'url_hash' => 'test-hash',
            'url' => 'https://example.com/brand',
            'name' => 'Test Brand',
            'logo' => 'https://example.com/logo.png',

        ];

        $response = $client->request('POST', '/brands', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
            'json' => $brandData,
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/contexts/Brand',
            '@type' => 'Brand',
            'name' => 'Test Brand',
            'url' => 'https://example.com/brand',
            'url_hash' => 'test-hash',
            'logo' => 'https://example.com/logo.png',
        ]);

    }

}
