<?php

namespace App\Services;

use App\DTO\Api\CategoryDTO;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CategoryService
{
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
     * Get all categories eId's.
     *
     * @return array
     */
    public static function getEIds(): array
    {
        return Category::query()->whereNotNull('eId')
            ->pluck('eId')
            ->toArray();
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
     * @param int|string $id
     * @param CategoryDTO $DTO
     * @return bool
     */
    public function update(int|string $id, CategoryDTO $DTO): bool
    {
        ($this->getById($id))->update([
            'title' => $DTO->title,
            'eId' => $DTO->eId,
        ]);

        return true;
    }
}
