<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\DTO\Api\CategoryDTO;

class CategoryService
{
    /**
     * Get all categories.
     *
     * @return Collection|array
     */
    public function getAll(): Collection|array
    {
        return Category::query()->get();
    }

    /**
     * Get category by id.
     *
     * @param int|string $id
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function getById(int|string $id): Model|Collection|Builder|array|null
    {
        return Category::query()->find($id);
    }

    /**
     * Store category.
     *
     * @param CategoryDTO $DTO
     * @return Model|Builder
     */
    public function store(CategoryDTO $DTO): Model|Builder
    {
        return Category::query()->create($DTO->all());
    }

    /**
     * Update category.
     *
     * @param string $method
     * @param int|string $id
     * @param CategoryDTO $DTO
     * @return bool
     */
    public function update(string $method, int|string $id, CategoryDTO $DTO): bool
    {
        $category = $this->getById($id);

        if (empty($category)) return false;

        /**
         * Если метод = PATCH, то обновляем только переданные поля,
         * Если метод = PUT, то обновляем переданые поля, а отсутствующие заменяем на NULL
         */
        foreach ($category->toArray() as $fieldName => $fieldValue) {

            if ($fieldName === 'id') continue;

            $updateData[$fieldName] = $method === 'PATCH'
                ? ($DTO->{$fieldName} ?? $fieldValue)
                : ($DTO->{$fieldName} ?? NULL);
        }

        // Обновляем основные поля
        $category->update($updateData ?? []);

        // Синхронизируем товары
        // TODO

        return true;
    }

    /**
     * Destroy category.
     *
     * @param string|int $id
     * @return bool
     */
    public function destroy(string|int $id): bool
    {
        $category = $this->getById($id);

        return !empty($category) ? $category->delete() : false;
    }
}
