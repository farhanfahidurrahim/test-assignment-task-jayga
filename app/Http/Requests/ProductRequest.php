<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $productId = $this->route('product') ?? $this->route('id');

        return [
            'name' => 'required|string|max:255|unique:products,name,' . $productId,
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'attributes' => 'required|array|min:1',
            'attributes.*.id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string'
        ];
    }

    // custom validation msg
    public function messages(): array
    {
        return [
            'attributes.required' => 'You must select at least one attribute.',
            'attributes.min' => 'At least one attribute is required.',
            'attributes.*.value.required' => 'Attribute value is required.'
        ];
    }
}
