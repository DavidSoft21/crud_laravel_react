<?php

namespace App\Http\Controllers;

use App\Models\module_coffe_store\product;
use App\Http\Requests\StoreproductRequest;
use App\Http\Requests\UpdateproductRequest;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private string $error_registro_existente = "Ya existe un registro con nombre similar";
    private string $registro_no_encontrado = "El registro no existe!";
    private string $registro_insertado = "Registro insertado con exito!";
    private string $registro_no_insertado = "Registro no insertado con exito!";
    private string $registro_editado = "Registro editado con exito!";
    private string $registro_no_editado = "Registro no editado con exito!";
    private string $registro_eliminado = "Registro eliminado con exito!";
    private string $registro_no_eliminado = "Registro no eliminado con exito!";
    private string $tipo_dato_incorrecto = "Tipo de dato incorrecto!";

    public function index()
    {
        $product_result = product::All();
        $products = [];
        $index = 0;
        foreach ($product_result as $product) {

            $products[$index]['id'] = $product->id;
            $products[$index]['name'] = $product->name;
            $products[$index]['price'] = product::money($product->price);
            $products[$index]['stock'] = $product->stock;
            $products[$index]['created_at'] = $product->created_at;
            $products[$index]['updated_at'] = $product->updated_at;
            $products[$index]['reference'] = $product->reference;
            $products[$index]['category'] = $product->category;
            $index++;
        }

        $enum_categorias = product::enum();
        $enum_referencias = product::enum(columns: 'reference');
        return response()->json($products);
        //return View::make('coffe_store.product.index')->with(compact('products', 'enum_categorias', 'enum_referencias'));
    }

    public function categorias(){
        $enum_categorias = product::enum();
        return response()->json($enum_categorias);
    }

    public function referencias()
    {
        $enum_referencias = product::enum(columns: 'reference');
        return response()->json($enum_referencias);
    }



    public function product(Request $request)
    {
        $products = product::find($request->id);
        return response()->json($products::find($request->id));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreproductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreproductRequest $request)
    {
        $json = [];
        $product_result = product::where('name', $request->name)->get();

        if (!$product_result->isEmpty()) {
            return response()->json(['successful' => false, 'message' => $this->error_registro_existente, 'data' => $product_result]);
        } else {
            $json = $request->createProduct();
            return response()->json(['successful' => true, 'message' => $this->registro_insertado, 'data' => $json]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\module_coffe_store\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\module_coffe_store\product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(product $product)
    {
        //
    }

    public function actualizar(UpdateproductRequest $request, $id)
    {
        $json = [];
        $product_result = product::where('name', $request->name)->first();

        if ($product_result == null) {

            $product = product::find($request->id);
            if ($product != null) {
                $json = $request->updateProduct($product, $request->id);
                return response()->json(['successful' => true, 'message' => $this->registro_editado, 'data' => $json]);
            } else {
                return response()->json(['successful' => false, 'message' => $this->registro_no_editado, 'data' => $json]);
            }
        } else {

            $product = product::find($request->id);
            if ($product != null && $product_result->id == $request->id) {
                $json = $request->updateProduct($product, $request->id);
                return response()->json(['successful' => true, 'message' => $this->registro_editado, 'data' => $json]);
            } else {
                return response()->json(['successful' => false, 'message' => $this->error_registro_existente, 'data' => $json]);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateproductRequest  $request
     * @param  \App\Models\module_coffe_store\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
    }

    public function eliminar(Request $request)
    {
        $id = intVal($request->id);

        if (is_int($id) == false || isset($id) == false) {
            return response()->json(['successful' => false, 'message' => $this->tipo_dato_incorrecto, 'data' => []]);
        } else {
            $product = product::find($id);
            if ($product != null) {
                $sales = $product->sales($id);
                if ($sales->isEmpty() == true) {
                    $json = $product;
                    $product->delete();
                    return response()->json(['successful' => true, 'message' => $this->registro_eliminado, 'data' => $json]);
                } else {
                    return response()->json(['successful' => false, 'message' => "existen ventas asociadas al producto", 'data' => []]);
                }
            } else {
                return response()->json(['successful' => false, 'message' => $this->registro_no_eliminado, 'data' => []]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\module_coffe_store\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
    }
}
