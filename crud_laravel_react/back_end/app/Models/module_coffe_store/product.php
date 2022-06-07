<?php

namespace App\Models\module_coffe_store;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\module_coffe_store\sale;

class product extends Model
{
    #declaracion de variables
    use HasFactory;
    public $timestamps = false;
    protected $table = 'products';
    protected $fillable = [
        'name',
        'reference',
        'price',
        'category',
        'stock'
    ];

    /** 
     * función que represanta la relación entre productos y ventas
     * su retorno es la ventas de un producto específico.
     * 
     */
    public function sales($id)
    {
        $sales = DB::table('sales')
            ->select(

                'sales.id as sale_id',
                'sales.employee as nombre_empleado',
                'sales.coffe_store as tienda',
                'sales.payment_method as metodo_pago',
                'sales.created_at as fecha_registro',
                'sales.updated_at as fecha_actualizacion',
                'products.id as producto_id',
                'products.category as categoria',
                'products.name as nombre',
                'products.reference as referencia',
                'products.stock as stock',
                'product_sale.amount as cantidad',

            )
            ->join('product_sale', 'sales.id', '=', 'product_sale.sale_id')
            ->join('products', 'product_sale.product_id', '=', 'products.id')
            ->where('product_sale.product_id', '=', $id)
            ->get();

        return $sales;
    }

    public static function money(int|float $number = 0, int $decimals = 0)
    {
        $f_number = number_format($number, $decimals);
        return  $f_number;
    }

    public static function enum(string $table = 'products', string $columns = 'category')
    {

        $enum = DB::select("
        SELECT column_type
        FROM information_schema.COLUMNS
        WHERE table_schema = 'heroku_1a9edf4cf09b82d'
            AND TABLE_NAME = '$table' 
            AND column_name = '$columns';
        ");

        $string = "";
        foreach ($enum[0] as $str) {
            $string = $str;
        }

        $start = strpos($string, 'enum(');
        $end = strpos($string, ')');

        $str = substr($string, 5, $end - 5);
        $array_enum = explode(",", $str);

        $arr_enum = array();

        foreach ($array_enum as $key => $str) {

            $start = strpos($str, "'");
            $end = strpos($str, "'");
            $value = substr($str, 1, $end - 1);
            $arr_enum[$key] = $value;
        }

        return $arr_enum;
    }
}
