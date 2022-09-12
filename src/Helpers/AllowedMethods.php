<?php

namespace Shreejalmaharjan27\Wooclient\Helpers;

use Exception;

class AllowedMethods {

    protected static array $methods = [
        'context' => [
            'view',
            'edit'
        ],
        'display' => [
            "default",
            "products",
            "subcategories",
            "both"
        ],
        'order' => [
            'asc',
            'desc'
        ],
        'orderby' => [
            'id',
            'include',
            'name',
            'slug',
            'term_group',
            'description',
            'count'
        ],
        'orderby_product' => [
            'date',
            'id',
            'include',
            'title',
            'slug',
            'price',
            'popularity',
            'rating'
        ],
        'status' => [
            'any',
            'draft',
            'pending',
            'private',
            'publish'
        ],
        'stock_status' => [
            'instock',
            'outofstock',
            'onbackorder'
        ],
        'type' => [
            'simple',
            'grouped',
            'external',
            'variable'
        ],
        'tax_class' => [
            'standard',
            'reduced-rate',
            'zero-rate'
        ],
    ];

    public static function validate(string $for, string $parameter, string $option): bool
    {
        $for = \strtolower($for);
        $parameter= \strtolower($parameter);
        $option = \strtolower($option);

        if(
            $for == 'product' 
            && $parameter == 'orderby'
        ) {
            $parameter = 'orderby_product';
        }

        if (isset(self::$methods[$parameter])) {
            if (in_array($option,self::$methods[$parameter])) {
                return true;
            } else {
                $allowedParams = \implode(", ",self::$methods[$parameter]);
                throw new Exception("Invalid parameters provided. Allowed options for '$parameter' are \"{$allowedParams}\"");
            }
        } else {
            throw new Exception('Unknown Parameter');
        }
        
    }
}