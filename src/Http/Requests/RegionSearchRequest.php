<?php

namespace DyanGalih\LaravelRegion\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegionSearchRequest extends FormRequest
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
            'q' => ['nullable', 'string', 'max:255'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'province_id' => ['nullable', 'integer'],
            'regency_id' => ['nullable', 'integer'],
            'district_id' => ['nullable', 'integer'],
        ];
    }
}
