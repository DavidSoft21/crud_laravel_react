<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\module_coffe_store\product;

class UpdateproductRequest extends FormRequest
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
     * 
     * */
    public function rules()
    {
        return [
            'name' => 'required',
            'reference' => 'required',
            'category' => 'required',
            'price' => 'required',
            'stock' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Añade un nombre',
            'reference.required' => 'Añade una referencia',
            'category.required' => 'Añade un categoria',
            'price.required' => 'Añade un precio',
            'stock.required' => 'Añade un stock'
        ];
    }

    public function updateProduct($product, $id)
    {
        $datos = $this->validated();
        $product_update = $product->update([
            'name' => trim(strtolower($datos['name'])),
            'reference' => trim(strtolower($datos['reference'])),
            'price' => trim(strtolower($datos['price'])),
            'stock' => trim(strtolower($datos['stock'])),
            'category' => trim(strtolower($datos['category']))
        ]);

        return $product;
    }
}
