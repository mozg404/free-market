<?php

namespace App\Http\Requests\MyProduct;

use App\Models\Category;
use App\Support\Price;
use Illuminate\Foundation\Http\FormRequest;

class ProductChangePriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'price_base' => ['required', 'numeric', 'min:10'],
            'price_discount' => ['sometimes', 'nullable', 'numeric'],
        ];
    }

    public function getPrice(): Price
    {
        return Price::fromBaseAndDiscount($this->input('price_base'), $this->input('price_discount'));
    }
}
