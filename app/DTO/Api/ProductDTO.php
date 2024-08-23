<?php

namespace App\DTO\Api;

use App\DTO\DTO;

class ProductDTO extends DTO
{
    /**
     * @param string $title
     * @param int|string $price
     * @param array|null $categoriesEId
     * @param int|string|null $eId
     */
    public function __construct(
        public string $title,
        public int|string|null $eId,
        public array|null $categoriesEId,
        public int|string $price = 0,
    ) {}
}
