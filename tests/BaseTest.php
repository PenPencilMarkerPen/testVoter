<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\Foundry\Test\Factories;

abstract class BaseTest extends ApiTestCase {
 
    use ResetDatabase, Factories;

    protected function createClientWithCredentials()
    {
        return static::createClient([], ['headers' => [
            'accept' => 'application/ld+json',
            'Content-Type' => 'application/ld+json',
        ]]);
    }


    protected function createClientWithToken(?string $token = null)
    {
        $token = $token ?: $this->getToken();

        return static::createClient([], ['headers' => [
            'authorization' => 'Bearer '.$token,
        ]]);
    }

    protected function getToken($body = []){

        static::createClient()->request('POST', '/api/register',
        [
            'headers' => [
                'accept' => 'application/ld+json',
                'Content-Type' => 'application/ld+json',
            ],
            $body ?: 'json' => [
                'email' => 'holly1111@yahoo.com',
                'password' => '12245',
            ]
        ]);
        

        $response = static::createClient()->request('POST', '/api/token/login', [
            'json' => $body ?: [
                'email' => 'holly1111@yahoo.com',
                'password' => '12245',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $data = $response->toArray();

        return $data['token'];
    }
}