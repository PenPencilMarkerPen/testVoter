<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
use App\Factory\UserFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersTest extends BaseTest {

    public function testGetCollection(): void {

        UserFactory::createMany(20);

        $response = static::createClient()->request('GET', '/api/users');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/User',
            '@id'=> '/api/users',
            '@type' => 'Collection',
            'totalItems' => 20,
            'view' => [
                '@id' => '/api/users?page=1',
                '@type'=> 'PartialCollectionView',
                'first'=> '/api/users?page=1',
                'last'=> '/api/users?page=2',
                'next'=> '/api/users?page=2'
            ]
        ]);

        $this->assertCount(10, $response->toArray()['member']);

        $this->assertMatchesResourceCollectionJsonSchema(User::class);
    }

    public function testRegisterUser():void {

        $response = $this->createClientWithCredentials()->request(Request::METHOD_POST, '/api/register',
        [
            'json' => [
                'email' => 'holly1111@yahoo.com',
                'password' => '12245',
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            'email'=> 'holly1111@yahoo.com',
            'brands' => [],
        ]);

        $this->assertMatchesResourceItemJsonSchema(User::class);

    }
}