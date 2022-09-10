<?php

namespace Shreejalmaharjan27\Wooclient;

use Shreejalmaharjan27\Wooclient\WooClient;

class Category {

    protected WooClient $client;

    public function __construct(WooClient $client)
    {
      $this->client = $client;
    }

    /**
     * Creates a Product Category and returns its id
     *
     * @param string $name
     * @param string|null $description
     * @param string|null $image Category Thumbnail
     * @param string|null $slug
     * @param integer|null $parentCategory
     * @param string $display
     * @param integer|null $menuOrder
     *
     * @return integer Category ID
     */
    public function create(
        string $name,
        string $description = null,
        string $image = null,
        string $slug = null,
        int $parentCategory = null,
        string $display = 'default',
        int $menuOrder = null
    ): int {
        $allowedDisplay = [
            "default",
            "products",
            "subcategories",
            "both"
        ];
        if(!in_array(strtolower($display), $allowedDisplay)) {
            $allowedDisplayList = implode(', ', $allowedDisplay);
            throw new \Exception("The allowed options to use in display are {$allowedDisplayList}.");
        }

        $json = [
            "name" => $name,
            "slug" => $slug,
            "parent" => $parentCategory,
            "description" => $description,
            "display" => strtolower($display),
            "image" => [
                "src" => $image ?? $this->client->wp."wp-content/uploads/woocommerce-placeholder.png",
            ],
            "menu_order" => $menuOrder
        ];

        $data = $this->client->request('/products/categories',$json,'post');
        return $data['id'];
    }


    /**
     * Tries to create a new Category.
     *  If it doesn't exist and returns it's id; if it already exists then returns Category ID
     *
     * @param string $name
     * @param string|null $description
     * @param string|null $image
     * @param string|null $slug
     * @param integer|null $parentCategory
     * @param string $display
     * @param integer|null $menuOrder
     *
     * @return integer
     */
    public function createIfNotExists(
        string $name,
        string $description = null,
        string $image = null,
        string $slug = null,
        int $parentCategory = null,
        string $display = 'default',
        int $menuOrder = null
    ): int {
        try {
            // try to create a category
            return $this->create($name,$description,$image,$slug,$parentCategory,$display,$menuOrder);
        } catch(\Exception $e) {

            // check if the error is due to category already being available
            if ($this->client->error['code'] === 'term_exists') {
                return $this->client->error['data']['resource_id'];
            } else {
                // if not then throw the error that caused the error ◑﹏◐
                throw new \Exception($e->getMessage());
            }
        }
    }


    /**
     * Gets all Categories
     *
     * @param integer $page 
     * @param integer $limit
     * @param string $order
     * @param string $orderBy
     * @param boolean $hideEmpty
     *
     * @return array Categories
     */
    public function getAllCategories(
        int $page = 1,
        int $limit = 10,
        string $order = 'asc',
        string $orderBy = 'name',
        bool $hideEmpty = false,
    ): array {

        $allowedOrder = [
            'asc',
            'desc'
        ];
        if (!\in_array(strtolower($order),$allowedOrder)) {
            $allowedOrderList = implode(', ', $allowedOrder);
            throw new \Exception("The allowed options to use in display are {$allowedOrderList}.");
        }

        $allowedOrderBy = [
            'id',
            'include',
            'name',
            'slug',
            'term_group',
            'description',
            'count'
        ];
        if (!\in_array(strtolower($orderBy),$allowedOrderBy)) {
            $allowedOrderByList = implode(', ', $allowedOrderBy);
            throw new \Exception("The allowed options to use in display are {$allowedOrderByList}.");
        }

        $httpRequest = \http_build_query([
            'page' => $page,
            'per_page' => $limit,
            'order' => $order,
            'orderby' => $orderBy,
            'hide_empty' => $hideEmpty
        ]);


        return $this->client->request("/products/categories?$httpRequest",null,'get');
    }


    /**
     * Gets a category By its ID
     *
     * @param integer $id
     *
     * @return array
     */
    public function getById(int $id): array
    {
        if(!$id) throw new \Exception('Unable to get Uncategorized Category by ID');
        return $this->client->request("/products/categories/$id");
    }


    /**
     * Search for Category
     *
     * @param string $query Category you want to search for
     * @param integer $page
     * @param integer $limit
     *
     * @return array Categories
     */
    public function search(string $query, int $page = 1, int $limit = 10): array
    {
        $httpRequest = \http_build_query([
            'search' => $query,
            'page' => $page,
            'per_page' => $limit
        ]);

        return $this->client->request("/products/categories/?$httpRequest");
    }
}