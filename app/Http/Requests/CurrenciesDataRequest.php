<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CurrenciesDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "code" => ["string"],
            "code_list" => ["array"],
            "code_list.*" => ["string"],
            "number" => [
                "exclude_unless:code,null",
                "exclude_unless:code_list,null",
                "exclude_unless:number_lists,null",
                "required",
                "array"],
            "number.*" => ["integer"],
            "number_lists" => ["array"],
            "number_lists.*" => ["integer"],
        ];
    }
}
