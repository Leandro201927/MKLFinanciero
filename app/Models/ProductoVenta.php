<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoVenta extends Model
{
    protected $table = 'producto_venta';
    protected $primaryKey = 'ID';
    public $timestamps = true;

    protected $fillable = [
        'VentaID', 
        'ProductoID', 
        'Cantidad_Productos', 
        'Valor_Unitario',
        'Valor_Total'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'ProductoID');
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class, 'VentaID');
    }
}