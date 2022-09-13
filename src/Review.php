<?php

namespace Shreejalmaharjan27\Wooclient;

use FFI\Exception;

class Review {

    protected WooClient $client;

    public function __construct(WooClient $client)
    {
      $this->client = $client;
    }


    /**
     * Create a Review for a product
     *
     * @param integer $productId Product ID
     * @param string $reviewer Reviewer Name
     * @param string $reviewerEmail Reviewer Email
     * @param integer $rating Rating for Product (1-5 stars)  
     * @param string $review Your opinion on Review
     *
     * @return boolean
     */
    public function create(
        int $productId,
        string $reviewer,
        string $reviewerEmail,
        int $rating, 
        string $review,
    ): bool {

        if ($rating > 5) $rating = 5;
        if ($rating < 1) $rating = 1;
        if (!filter_var($reviewerEmail,FILTER_VALIDATE_EMAIL)) throw new Exception('Invalid email address');

        $json = [
            'product_id' => $productId,
            'review' => $review,
            'reviewer' => $reviewer,
            'reviewer_email' => $reviewerEmail,
            'rating' => $rating
        ];

        if($this->client->request('/products/reviews',$json,'post')) return true;

        return false;
    }


    /**
     * Get a Product Review by it's id
     *
     * @param integer $id review id
     *
     * @return array
     */
    public function getById(int $id): array
    {
        return $this->client->request("/products/reviews/$id");
    }
}