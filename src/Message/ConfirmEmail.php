<?php

namespace App\Message;

use App\Entity\Product;

class ConfirmEmail {

    public function __construct(
        private Product $product
    )
    {}

    public function getProduct(): Product
    {
        return $this->product;
    }

}