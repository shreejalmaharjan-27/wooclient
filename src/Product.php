<?php

namespace Shreejalmaharjan27\Wooclient;

use Shreejalmaharjan27\Wooclient\Helpers\AllowedMethods;
use Shreejalmaharjan27\Wooclient\Helpers\NumberModifier;
use Shreejalmaharjan27\Wooclient\Helpers\StringModifer;

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
     * @param array $category [ ['id'=>1], ['id' => 2] ] Product Category ID
     * @param string $type Product Type
     * @param array $attributes Product Attributes [ ['name' => ..., 'options' => [...]] ]
     *
     * @return boolean
     */
    public function create(
        string $name,
        float $price = 0.00,
        string $description = null,
        string $shortDescription = null,
        string $image = null,
        array $category = [],
        string $type = 'simple',
        array $attributes = []
    ): bool {
        if(
            $shortDescription ?? false
            && $description
        ) {
            $shortDescription = StringModifer::truncate($description,20);
        }

        $categories = [];

        if ($category ?? false) {
            foreach($category as $cat) {
                $categories[] = [
                    'id'=>$cat
                ];
            }
        }
        

        $json = [
            "name" => $name,
            "type" => $type,
            "regular_price" => strval($price),
            "description" => $description,
            "short_description" => $shortDescription,
            "categories" => $categories,
            "images" => [
                [
                    'src' => $image ?? $this->client->wp."wp-content/uploads/woocommerce-placeholder.png"
                ]
            ],
            'attributes' => $attributes
        ];

        if ($this->client->request('/products',$json)) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Gets all Products
     *
     * @param integer $page
     * @param integer $limit
     * @param string $order
     * @param string $orderBy
     * @param integer|null $category
     * @param integer|null $tag
     * @param string $status
     * @param string $type
     * @param boolean $featured
     * @param boolean $onSale
     * @param float|null $minPrice
     * @param float|null $maxPrice
     * @param string $stockStatus
     * @param string|null $sku
     * @param string|null $beforeDate
     * @param string|null $afterDate
     *
     * @return array Products
     */
    public function getAllProducts(
        int $page = 1,
        int $limit = 10,
        string $order = 'asc',
        string $orderBy = 'date',
        int $category = null,
        int $tag = null,
        string $status = 'any',
        string $type = 'simple',
        bool $featured = false,
        bool $onSale = false,
        float $minPrice = null,
        float $maxPrice = null,
        string $stockStatus = 'any',
        string $sku = null,
        string $beforeDate = null, 
        string $afterDate = null
    ): array {
        $order = \strtolower($order);
        $orderBy = \strtolower($orderBy);
        $status = \strtolower($status);
        $type = \strtolower($type);
        $stockStatus = (\strtolower($stockStatus) == 'any') ? null : \strtolower($stockStatus);

        AllowedMethods::validate('product','order',$order);
        AllowedMethods::validate('product','orderby',$orderBy);
        AllowedMethods::validate('product','status',$status);
        AllowedMethods::validate('product','type',$type);
        ($stockStatus ?? false) ?  AllowedMethods::validate('product','stock_status',$stockStatus) : '';

        $httpRequest = \http_build_query([
            'page' => $page,
            'per_page' => $limit,
            'order' => $order,
            'order_by' => $orderBy,
            'category' => strval($category),
            'tag' => strval($tag),
            'status' => $status,
            'type' => $type,
            'featured' => $featured,
            'on_sale' => $onSale,
            'min_price' => NumberModifier::floatZeroIfZero($minPrice),
            'max_price' => NumberModifier::floatZeroIfZero($maxPrice),
            'stock_status' => $stockStatus,
            'sku' => $sku,
            'before' => $beforeDate,
            'after' => $afterDate
        ]);

        return $this->client->request("/products?$httpRequest");
    }
}