<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\DTO\Api\ProductDTO;
use App\Models\Product;

class ProductService
{
    /**
     * Get all products.
     *
     * @return Collection|array
     */
    public function getAll(): Collection|array
    {
        return Product::query()->get();
    }

    /**
     * Get product by id.
     *
     * @param int|string $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function getById(int|string $id): Model|Collection|Builder|array|null
    {
        return Product::query()->find($id);
    }

    /**
     * Store product.
     *
     * @param ProductDTO $DTO
     * @return Model|Builder
     */
    public function store(ProductDTO $DTO): Model|Builder
    {
        $product = Product::query()->create($DTO->all());

        empty($DTO->categoriesEId) || $product->categories()->sync($DTO->categoriesEId);

        return $product;
    }

    /**
     * Update product.
     *
     * @param string $method
     * @param int|string $id
     * @param ProductDTO $DTO
     * @return bool
     */
    public function update(string $method, int|string $id, ProductDTO $DTO): bool
    {
        $product = $this->getById($id);

        if (empty($product)) return false;

        /**
         * Если метод = PATCH, то обновляем только переданные поля,
         * Если метод = PUT, то обновляем переданые поля, а отсутствующие заменяем на NULL
         */
        foreach ($product->toArray() as $fieldName => $fieldValue) {

            if ($fieldName === 'id') continue;

            $updateData[$fieldName] = $method === 'PATCH'
                ? ($DTO->{$fieldName} ?? $fieldValue)
                : ($DTO->{$fieldName} ?? NULL);
        }

        // Обновляем основные поля
        $product->update($updateData ?? []);

        // Связываем с категориями
        ($method === 'PATCH' && !empty($DTO->categoriesEId))
            ? $product->categories()->syncWithoutDetaching($DTO->categoriesEId)
            : $product->categories()->sync($DTO->categoriesEId);

        return true;
    }

    /**
     * Destroy product.
     *
     * @param string|int $id
     * @return bool
     */
    public function destroy(string|int $id): bool
    {
        $product = $this->getById($id);

        return !empty($product) ? $product->delete() : false;
    }
}
