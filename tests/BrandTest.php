<?php

namespace App\Tests;

use App\Entity\Brand;
use App\Factory\BrandFactory;
use Symfony\Component\HttpFoundation\Response;

class BrandTest extends BaseTest {


    public function testGetCollection():void {

        BrandFactory::createMany(100);

        static::createClient()->request('GET', '/api/brands');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testGetBrand():void
    {
        $user = $this->getUserCurrent();

        BrandFactory::createOne(['users' => $user]);

        $brand = BrandFactory::first();
        $user = $brand->users;

        $token = $this->getToken([
            'email' => $user->email,
            'password' => $this->password,
        ]);
        
        $this->createClientWithToken($token)->request('GET', '/api/brands/'.$brand->id);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostBrand():void {

        $user= $this->getUserCurrent();

        $token = $this->getToken([
            'email' => $user->email,
            'password' => $this->password,
        ]);

        $this->createClientWithCredentials($token)->request('POST', '/api/brands', [
            'json' => [
                'name' => 'testName',
                'users' => '/api/users/'.$user->id,
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
    
    public function testDeleteBrand(): void {

        $user= $this->getUserCurrent();

        BrandFactory::createOne(['users' => $user]);

        $brand = BrandFactory::first();

        $token = $this->getToken([
            'email' => $user->email,
            'password' => $this->password,
        ]);

        $this->createClientWithToken($token)->request('DELETE', '/api/brands/'.$brand->id);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

}