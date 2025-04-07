<?php

namespace App\Http\Requests;

use App\DTO\CreateCategoryDTO;
use Illuminate\Foundation\Http\FormRequest;

class storeCategoryRequest extends FormRequest
{

    public CreateCategoryDTO $createCategoryDTO;

    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'name' => 'ahmad'
        ]);
    }


    public function rules(): array
    {
        return [
            'name' => ['required'],
        ];
    }

    public function passedValidation()
    {
        $this->createCategoryDTO = new CreateCategoryDTO($this->input('name'));
    }
}
