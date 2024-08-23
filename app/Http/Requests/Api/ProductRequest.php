<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\Traits\RequestValidateTrait;
use App\Services\CategoryService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class ProductRequest extends FormRequest
{
    use RequestValidateTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation headers and json structure.
     */
    public function __construct(Request $request)
    {
        parent::__construct();

        if (!$this->validateHeaders($request)) {
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => __('validation.request_headers_is_invalid', ['type' => 'application/json']),
            ])->setStatusCode(400));
        }

        if (!$this->validateJson($request)) {
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => __('validation.request_has_invalid_json_or_empty'),
            ])->setStatusCode(400));
        }
    }

    /**
     * Return validation errors as json response.
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => $validator->errors()->first(),
        ])->setStatusCode(400));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:12',
            'price' => 'required|numeric|min:0|max:200',
            'eId' => 'integer|nullable',
            'categoriesEId' => 'array|in:' . implode(',', CategoryService::getEIds()),
        ];
    }

    /**
     * Validation messages.
     *
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'title.required' => __('validation.field_is_required', ['name' => 'title']),
            'title.string' => __('validation.field_must_be', ['name' => 'title', 'type' => 'string']),
            'title.min' => __('validation.field_out_of_range', ['name' => 'title', 'min' => 3, 'max' => 12]),
            'title.max' => __('validation.field_out_of_range', ['name' => 'title', 'min' => 3, 'max' => 12]),
            'price.required' => __('validation.field_is_required', ['name' => 'price']),
            'price.numeric' => __('validation.field_must_be', ['name' => 'title', 'type' => 'integer/float']),
            'price.min' => __('validation.field_out_of_range', ['name' => 'title', 'min' => 0, 'max' => 200]),
            'price.max' => __('validation.field_out_of_range', ['name' => 'title', 'min' => 0, 'max' => 200]),
            'eId.integer' => __('validation.field_must_be', ['name' => 'eId', 'type' => 'integer']),
            'categoriesEId.array' => __('validation.field_must_be', ['name' => 'categoriesEId', 'type' => 'array']),
            'categoriesEId.in' => __('validation.object_for_sync_not_found', ['name' => 'categoriesEId']),
        ];
    }
}
