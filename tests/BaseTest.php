<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\Foundry\Test\Factories;

abstract class BaseTest extends ApiTestCase {
 
    use ResetDatabase, Factories;

    protected string $password;

    protected function createClientWithCredentials(?string $token = null)
    {
        return static::createClient([], ['headers' => [
            'accept' => 'application/ld+json',
            'Content-Type' => 'application/ld+json',
            'authorization' => 'Bearer '.$token,
        ]]);
    }


    protected function createClientWithToken(?string $token = null)
    {
        $token = $token ?: $this->getToken();

        return static::createClient([], ['headers' => [
            'authorization' => 'Bearer '.$token,
        ]]);
    }

    protected function getToken($body = []): string {
       

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

    protected function getUserCurrent(): User {

        $passwordHash = '$2y$13$wUFdnctpky5vSqWsyGt5cONkKQkah23JqWodiSNDaq5xaEidHKz6u';

        $this->password = 'string';

        $user  = UserFactory::createOne(['password' => $passwordHash]);
        
        return $user;
    }
}