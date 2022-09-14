<?php

namespace Shreejalmaharjan27\Wooclient;

use FFI\Exception;

class Attribute {
    protected WooClient $client;

    public function __construct(WooClient $client)
    {
      $this->client = $client;
    }


    /**
     * Creates a Product Attribute
     *
     * @param string $name
     * @param string $slug
     *
     * @return integer
     */
    public function create(
        string $name,
        string $slug = null
        ): int {
        $json = [
            'name' => $name,
            'slug' => $slug
        ];
        
        $data = $this->client->request('/products/attributes',$json);
        return $data['id'];
    }

    
    /**
     * Creates an Attribute if it doesn't exist
     *
     * @param string $name
     * @param string $slug
     *
     * @return integer|null
     */
    public function createIfNotExists(string $name, string $slug = null): int|null
    {
        try {
            return $this->create($name);
        } catch(Exception $e) {
           $all = $this->getAllAttributes();
           foreach($all as $arr)
           {
            if ($arr['name'] === $name) {
                return $arr['id'];
            }
           }
           return null;
        }
    }

    /**
     * Gets all Attributes
     *
     * @return array
     */
    public function getAllAttributes(): array
    {
        return $this->client->request('/products/attributes');
    }

}