<?php

namespace App\DTO;

class DTO
{
    /**
     * Creating DTO from request.
     *
     * @param $request
     * @return static
     */
    public static function fromRequest($request): static
    {
        foreach (get_class_vars(static::class) as $fieldName => $fieldValue) {
            $dataMapper[$fieldName] = $request->{$fieldName} ?? null;
        }

        $classToMap = static::class;

        return new $classToMap(...$dataMapper ?? []);
    }

    /**
     * Get all vars of DTO.
     *
     * @return array
     */
    public function all(): array
    {
        foreach ($this ?? [] as $fieldName => $fieldValue) {
            $dataMapper[$fieldName] = $fieldValue;
        }

        return $dataMapper ?? [];
    }
}
