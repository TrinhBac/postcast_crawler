<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class ListPostcastRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_date' => ['date_format:Y-m-d', 'before_or_equal:today'],
            'end_date'   => ['date_format:Y-m-d', 'after_or_equal:start_date'],
            'limit'      => ['integer', 'min:0'],
        ];
    }

    protected function failedValidation(Validator $validator) : bool
    {
        return $this->baseFailedValidation($validator);
    }
}
