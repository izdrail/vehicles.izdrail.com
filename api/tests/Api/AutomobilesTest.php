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
        // You might want to add more assertions here, e.g., checking the response content type or structure
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testCreateAuto(): void
    {
        $client = static::createClient();

        // 1. Create a Brand resource first
        $brandData = [
            'url_hash' => 'test-brand-hash-' . uniqid(), // Use unique values to avoid conflicts
            'url' => 'https://example.com/brand/' . uniqid(),
            'name' => 'Test Brand ' . uniqid(),
            'logo' => 'https://example.com/logo.png',
        ];

        $brandResponse = $client->request('POST', '/brands', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
            'json' => $brandData,
        ]);

        // Assert brand creation was successful
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // 2. Capture the actual ID/URI of the newly created Brand
        $brandResponseData = $brandResponse->toArray();
        $brandId = $brandResponseData['@id'] ?? null; // Get the @id property


        // 3. Use the captured Brand ID when creating the Automobile
        $automobileData = [
            'url_hash' => 'auto-test-hash-' . uniqid(), // Use unique values
            'url' => 'https://example.com/automobile/' . uniqid(),
            'brand' => $brandId, // Use the actual captured brand URI
            'name' => 'Test Automobile ' . uniqid(),
            'description' => 'This is a test automobile description',
            'press_release' => 'https://example.com/press-release/' . uniqid(),
            'photos' => 'https://example.com/photos/car-' . uniqid() . '.jpg',
            'vehicle_type' => 'sedan',
            // Add other required fields if any
        ];

        $automobileResponse = $client->request('POST', '/automobiles', [
            'headers' => [
                'Content-Type' => 'application/ld+json',
                'Accept' => 'application/ld+json',
            ],
            'json' => $automobileData,
        ]);

        // Assert automobile creation was successful
        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // 4. Assert the response contains the expected data, including the correct brand ID
        $this->assertJsonContains([
            // '@context' and '@type' are usually present if using JSON-LD
            '@context' => '/contexts/Automobile',
            '@type' => 'Automobile',
            'name' => $automobileData['name'], // Use data from your request
            'url' => $automobileData['url'],
            'url_hash' => $automobileData['url_hash'],
            'description' => $automobileData['description'],
            'press_release' => $automobileData['press_release'],
            'photos' => $automobileData['photos'],
            'vehicle_type' => $automobileData['vehicle_type'],
            'brand' => $brandId, // Assert against the actual brand URI used
        ]);

        // Optional: Assert that the created resource exists by making a GET request to its @id
        $automobileResponseData = $automobileResponse->toArray();
        $automobileId = $automobileResponseData['@id'] ?? null;

        $client->request('GET', $automobileId, [
             'headers' => ['Accept' => 'application/ld+json'],
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertJsonContains([
             '@id' => $automobileId,
             'name' => $automobileData['name'],
             'brand' => $brandId,
        ]);

        // $client->request('DELETE', $automobileId);
        // $this->assertResponseStatusCodeSame(204);
        // $client->request('DELETE', $brandId);
        // $this->assertResponseStatusCodeSame(204);
    }
}
