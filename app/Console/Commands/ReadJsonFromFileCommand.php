<?php

namespace App\Console\Commands;

use App\DTO\Api\CategoryDTO;
use App\DTO\Api\ProductDTO;
use App\Models\Category;
use App\Models\Product;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ReadJsonFromFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'json:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Читает и обрабатывает содержимое файлов ~/storage/app/exchange/**';

    /**
     * @var ProductService
     */
    private ProductService $productService;

    /**
     * @var CategoryService
     */
    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService, ProductService $productService)
    {
        parent::__construct();

        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Получаем полный путь
        $categoriesPath = Storage::path('/exchange/categories.json');
        $productsPath = Storage::path('/exchange/products.json');

        // Проверяем на фактическое существование файлов
        if (!file_exists($categoriesPath))
            die('Файл categories.json не найден' . PHP_EOL);

        if (!file_exists($productsPath))
            die('Файл products.json не найден' . PHP_EOL);

        // Обрабатываем categories.json
        $categories = json_decode(File::get($categoriesPath), true);

        foreach ($categories ?? [] as $category) {
            try {
                Validator::make($category, [
                    'title' => 'required|string|min:3|max:12',
                    'eId' => 'integer'
                ])->validate();

            } catch (ValidationException $e) {

                die ($e->getMessage() . ' (in file: categories.json) ' . PHP_EOL);
            }

            /**
             * Пытаемся найти сущность по eId, если не находим - создаем,
             * Иначе - обновляем
             */
            if (empty($category['eId'])) {
                $this->categoryService->store(CategoryDTO::from($category));
                continue;
            }

            $categoryDb = Category::query()->where('eId', '=', $category['eId'])->first();

            if (empty($categoryDb)) {
                $this->categoryService->store(CategoryDTO::from($category));
                continue;
            }

            $this->categoryService->update($categoryDb->id, CategoryDTO::from($category));
        }

        // Обрабатываем products.json
        $products = json_decode(File::get($productsPath), true);

        foreach ($products ?? [] as $product) {
            try {
                Validator::make($product, [
                    'title' => 'required|string|min:3|max:12',
                    'price' => 'required|numeric|min:0|max:200',
                    'eId' => 'integer|nullable',
                    'categoriesEId' => 'array|in:' . implode(',', CategoryService::getEIds()),
                ])->validate();

            } catch (ValidationException $e) {

                die ($e->getMessage() . ' (in file: products.json) ' . PHP_EOL);
            }

            /**
             * Пытаемся найти сущность по eId, если не находим - создаем,
             * Иначе - обновляем
             */
            if (empty($product['eId'])) {
                $this->productService->store(ProductDTO::from($product));
                continue;
            }

            $productDb = Product::query()->where('eId', '=', $product['eId'])->first();

            if (empty($productDb)) {
                $this->productService->store(ProductDTO::from($product));
                continue;
            }

            $this->productService->update('PATCH', $productDb->id, ProductDTO::from($product));
        }

        echo 'Команда выполнена.';
    }
}
