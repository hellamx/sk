<?php

namespace App\Http\Controllers\Api;

use App\DTO\Api\ProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductRequest;
use App\Http\Resources\Api\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    protected ProductService $service;

    /**
     * @param ProductService $service
     */
    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => ProductResource::collection($this->service->getAll()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return JsonResponse
     */
    public function store(ProductRequest $request): JsonResponse
    {
        return response()->json([
            'status' => true,
            'data' => $this->service->store(ProductDTO::from($request)),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $product = $this->service->getById($id);

        return response()
            ->json([
                'status' => !empty($product),
                'data' => $product ?? [],
            ])
            ->setStatusCode(!empty($product) ? 200 : 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(ProductRequest $request, string $id): JsonResponse
    {
        $isSuccess = $this->service->update($request->method(), $id, ProductDTO::from($request));;

        return response()
            ->json(array_merge(['status' => $isSuccess], $isSuccess ? [] : ['message' => __('validation.object_not_found', ['id' => $id])]))
            ->setStatusCode(!empty($category) ? 200 : 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $isSuccess = $this->service->destroy($id);

        return response()->json(array_merge([
            'status' => $isSuccess,
        ], $isSuccess ? [] : ['message' => __('validation.object_not_found', ['id' => $id])]));
    }
}
