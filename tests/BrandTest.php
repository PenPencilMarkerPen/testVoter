<?php

namespace App\Tests;

use App\Entity\Brand;
use App\Factory\BrandFactory;

class BrandTest extends BaseTest {


    public function testGetCollection():void {

        BrandFactory::createMany(100);

        static::createClient()->request('GET', '/api/brands');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testGetBrand():void
    {

        BrandFactory::createOne();
        // $token 

        // $this->getToken();

        // $brand = BrandFactory::new()->withoutPersisting()->create()->object();
        // $brand = BrandFactory::find(1);

        $brand = BrandFactory::first();
        $user = $brand->users;

        $token = $this->getToken([
            'email' => $user->email,
            'password' => $user->getPassword(),
        ]);
        
        dump($token);
    }


}