<?php

namespace Shreejalmaharjan27\Wooclient;

use FFI\Exception;

class Term {
    protected WooClient $client;

    public function __construct(WooClient $client)
    {
      $this->client = $client;
    }


    /**
     * Create an Attribute Term
     *
     * @param integer $attributeId
     * @param string $name
     * @param string $description
     * @param string|null $slug
     *
     * @return integer term id
     */
    public function create(
        int $attributeId,
        string $name,
        string $description = '',
        string $slug = null
    ): int {
        $json = [
            'name' => $name,
            'description' => $description,
            'slug' => $slug
        ];

        $data = $this->client->request("/products/attributes/$attributeId/terms",$json);
        return $data['id'];
    }


    /**
     * Create an Attribute term if it doesn't exist
     *
     * @param integer $attributeId
     * @param string $name
     * @param string $description
     * @param string|null $slug
     *
     * @return integer term id
     */
    public function createIfNotExists(
        int $attributeId,
        string $name,
        string $description = '',
        string $slug = null
    ): int {
        try {
            return $this->create($attributeId,$name,$description,$slug);
        }catch(Exception $e) {
            
            // check if the error is due to category already being available
            if ($this->client->error['code'] === 'term_exists') {
                return $this->client->error['data']['resource_id'];
            } else {
                // if not then throw the error that caused the error ◑﹏◐
                throw new Exception($e->getMessage());
            }
        }
    }
}