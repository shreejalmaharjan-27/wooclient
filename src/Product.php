<?php

namespace Shreejalmaharjan27\Wooclient;

class Product {
    protected WooClient $client;

    public function __construct(WooClient $client)
    {
      $this->client = $client;
    }

    /**
     * Create a Product
     *
     * @param string $name Product Name
     * @param float $price Price
     * @param string|null $description Description
     * @param string|null $shortDescription Short Description
     * @param string|null $image Product Image
     * @param integer $category Product Category ID
     * @param string $type Product Type
     *
     * @return boolean
     */
    public function create(
        string $name,
        float $price = 0.00,
        string $description = null,
        string $shortDescription = null,
        string $image = null,
        int $category = 0,
        string $type = 'simple',

    ): bool {
        if(
            $shortDescription ?? false
            && $description
        ) {
            $shortDescription = StringModifer::truncate($description,20);
        }

        $json = [
            "name" => $name,
            "type" => $type,
            "regular_price" => strval($price),
            "description" => $description,
            "short_description" => $shortDescription,
            "categories" => [
                [
                    "id" => $category
                ]
            ],
            "images" => [
                [
                    'src' => $image ?? $this->client->wp."wp-content/uploads/woocommerce-placeholder.png"
                ]
            ],
        ];

        if ($this->client->request('/products',$json)) {
            return true;
        } else {
            return false;
        }
    }
}