<?php

namespace App\DTO\Api;

use App\DTO\DTO;

class CategoryDTO extends DTO
{
    /**
     * @param string $title
     * @param int|string|null $eId
     */
    public function __construct(
        public string $title,
        public int|string|null $eId,
    ) {}
}
