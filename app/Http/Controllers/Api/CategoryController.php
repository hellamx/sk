<?php

namespace App\Http\Controllers\Api;

use App\DTO\Api\CategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CategoryRequest;
use App\Http\Resources\Api\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * @var CategoryService
     */
    protected CategoryService $service;

    /**
     * @param CategoryService $service
     */
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return array
     */
    public function index(): array
    {
        return [
            'status' => true,
            'data' => CategoryResource::collection($this->service->getAll()),
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->service->store(CategoryDTO::fromRequest($request)),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $category = $this->service->getById($id);

        return response()
            ->json([
                'status' => !empty($category),
                'data' => $category ?? [],
            ])
            ->setStatusCode(!empty($category) ? 200 : 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id): JsonResponse
    {
        $isSuccess = $this->service->update($request->method(), $id, CategoryDTO::fromRequest($request));;

        return response()
            ->json(array_merge(['status' => $isSuccess], $isSuccess ? [] : ['message' => __('validation.object_not_found', ['id' => $id])]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): array
    {
        $isSuccess = $this->service->destroy($id);

        return array_merge([
            'status' => $isSuccess,
        ], $isSuccess ? [] : ['message' => __('validation.object_not_found', ['id' => $id])]);
    }
}
