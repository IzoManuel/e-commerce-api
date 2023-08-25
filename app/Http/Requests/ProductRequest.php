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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [];
        
        $rules['name'] = 'required|max:255';
        $rules['categories']   = 'required|array';
        $rules['categories.*'] = 'exists:categories,id';
        //$rules['unit' ]         = 'required';
        $rules['min_quantity' ]      = 'required|numeric';
        $rules['unit_price']    = 'required|numeric';
        if($this->get('discount_type') == 'amount'){
            $rules['discount'] = 'required|numeric|lt:unit_price';
        }else{
            $rules['discount'] = 'required|numeric|lt:unit_price';
        }
        $rules['current_stock'] = 'required|numeric';
        $rules['product_images[]'] = 'file|image|mimes:jpeg,png,gif,jpg|max:2048';
        return $rules;
    }

        /**
     * Get the validation messages of rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'             => 'Product name is required',
            'category_id.required'      => 'Category is required',
            'unit.required'             => 'Unit field is required',
            'min_qty.required'          => 'Minimum purchase quantity is required',
            'min_qty.numeric'           => 'Minimum purchase must be numeric',
            'unit_price.required'       => 'Unit price is required',
            'unit_price.numeric'        => 'Unit price must be numeric',
            'discount.required'         => 'Discount is required',
            'discount.numeric'          => 'Discount must be numeric',
            'discount.lt:unit_price'    => 'Discount can not be gretaer than unit price',
            'current_stock.required'    => 'Current stock is required',
            'current_stock.numeric'     => 'Current stock must be numeric',
        ];
    }
}