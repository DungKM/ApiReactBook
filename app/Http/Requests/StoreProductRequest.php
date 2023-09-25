<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
    public function rules()
    {
        if (request()->isMethod('post')) {
            return [
                'name' => 'required|string|max:255', // Sửa max:258 thành max:255
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'required|string',
                'price' => 'required|numeric', // Sửa 'require' thành 'required|numeric'
                'quantity' => 'required|numeric', // Sửa 'require' thành 'required|numeric'
                'quantity_page' => 'required|numeric', // Sửa 'require' thành 'required|numeric'
                'sale' => 'required|numeric', // Sửa 'require' thành 'required|numeric'
                'category_id' => 'required|numeric', // Sửa 'require' thành 'required|numeric'
                'brand_id' => 'required|numeric', // Sửa 'require' thành 'required|numeric'
            ];
        } else {
            return [
                'name' => 'required|string|max:255', // Sửa max:258 thành max:255
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'required|string',
                'price' => 'required|numeric', // Sửa 'require' thành 'required|numeric'
                'quantity' => 'required|numeric', // Sửa 'require' thành 'required|numeric'
                'quantity_page' => 'required|numeric', // Sửa 'require' thành 'required|numeric'
                'sale' => 'required|numeric', // Sửa 'require' thành 'required|numeric'
                'category_id' => 'required|numeric', // Sửa 'require' thành 'required|numeric'
                'brand_id' => 'required|numeric', // Sửa 'require' thành 'required|numeric'
            ];
        }
    }
    
 
}