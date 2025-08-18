<?php

namespace App\Http\Requests\MyProduct;

use App\Models\Category;
use App\Support\Price;
use Illuminate\Foundation\Http\FormRequest;

class ProductChangeInstructionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'instruction' => ['required', 'string', 'min:5'],
        ];
    }
}
