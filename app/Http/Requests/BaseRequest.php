<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class BaseRequest extends FormRequest
{
    protected function baseFailedValidation(Validator $validator): bool
    {
        $errors = (new ValidationException($validator))->errors();
        if(empty($errors)){
            return true;
        }

        $validationErrors = [];
        foreach ($errors as $key => $value){
            $messageError = implode(' ', $value);
            $validationErrors[$key] = $messageError;
        }

        throw new HttpResponseException(
            response()->json([
                'success' =>  false,
                'data' => null,
                'message'  =>  "Validation error",
                'errorMessages'   =>  $validationErrors,
            ], 400)
        );
    }
}
