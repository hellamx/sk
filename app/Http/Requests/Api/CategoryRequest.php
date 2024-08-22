<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\Api\Traits\RequestValidateTrait;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class CategoryRequest extends FormRequest
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
            ]));
        }

        if (!$this->validateJson($request)) {
            throw new HttpResponseException(response()->json([
                'status' => false,
                'message' => __('validation.request_has_invalid_json_or_empty'),
            ]));
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
        ]));
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
            'eId' => 'integer'
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
            'eId.integer' => __('validation.field_must_be', ['name' => 'eId', 'type' => 'integer']),
        ];
    }
}
