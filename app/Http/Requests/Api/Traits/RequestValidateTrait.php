<?php

namespace App\Http\Requests\Api\Traits;

use Illuminate\Http\Request;
use JsonException;

trait RequestValidateTrait
{
    public function validateHeaders(Request $request): bool
    {
        return $request->header('content-type') === 'application/json';
    }

    public function validateJson(Request $request): bool
    {
        try {

            json_decode($request->getContent(), false, 512, JSON_THROW_ON_ERROR);

            return true;

        } catch (JsonException) {

            return false;

        }
    }
}
