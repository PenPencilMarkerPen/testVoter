<?php

namespace App\Tests;

use App\Factory\ProductFactory;
use App\Factory\BrandFactory;
use App\Factory\CategoryFactory;
use Symfony\Component\HttpFoundation\Response;

class ProductTest extends BaseTest {

    public function testGetCollection():void
    {
        ProductFactory::createMany(100);

        static::createClient()->request('GET', '/api/products');

        $this->assertResponseIsSuccessful();

        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testGetProduct(): void{

        $user = $this->getUserCurrent();

        BrandFactory::createOne(['users' => $user]);

        $brand = BrandFactory::first();

        ProductFactory::createOne(['brand' => $brand]);

        $product = ProductFactory::first();

        $token = $this->getToken([
            'email' => $user->email,
            'password' => $this->password,
        ]);

        $this->createClientWithToken($token)->request('GET', '/api/products/'.$product->id);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testPostProduct():void
    {
        $user = $this->getUserCurrent();

        BrandFactory::createOne(['users' => $user]);
        CategoryFactory::createOne();

        $brand = BrandFactory::first();
        $category = CategoryFactory::first();

        $token = $this->getToken([
            'email' => $user->email,
            'password' => $this->password,
        ]);


        $this->createClientWithCredentials($token)->request('POST', '/api/products', [
            'json' => [
                'name' => 'testName',
                'description' => 'description',
                'count' => 10000,
                'view' => 0,
                'isVisible' => true,
                'brand' => '/api/brands/'.$brand->id,
                'categories' => [
                    '/api/categories/'.$category->id
                ]
            ]
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    public function testDeleteProduct():void {

        $user = $this->getUserCurrent();

        BrandFactory::createOne(['users' => $user]);

        $brand = BrandFactory::first();

        ProductFactory::createOne(['brand' => $brand]);

        $product = ProductFactory::first();

        $token = $this->getToken([
            'email' => $user->email,
            'password' => $this->password,
        ]);

        $this->createClientWithToken($token)->request('DELETE', '/api/products/'.$product->id);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}