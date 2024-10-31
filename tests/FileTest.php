<?php

namespace App\Tests;

use App\Factory\ProductFactory;
use App\Factory\BrandFactory;
use App\Factory\FileFactory;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileTest extends BaseTest {

   
    public function testFileUpload():void {

        $filePath = getcwd().'/public/files/snimok-ekrana-ot-2024-10-25-11-03-30-671f61a2211b0001784214.png';

        $file = new UploadedFile(
            $filePath,
            'snimok-ekrana-ot-2024-10-25-11-03-30-671f61a2211b0001784214.png',
            'image/png',
        );

        $user = $this->getUserCurrent();

        BrandFactory::createOne(['users' => $user]);

        $brand = BrandFactory::first();

        ProductFactory::createOne(['brand' => $brand]);

        $product = ProductFactory::first();

        $token = $this->getToken([
            'email' => $user->email,
            'password' => $this->password,
        ]);

        $response = static::createClient()->request(
            'POST',
            '/api/files',
            [
                'headers' => [
                    'Content-Type' => 'multipart/form-data',
                    'authorization' => 'Bearer '.$token,
                ],
            ],
            [

                'file'=>$file,
                'product' => '/api/products/'.$product->id,
            ]
        );

    }

}