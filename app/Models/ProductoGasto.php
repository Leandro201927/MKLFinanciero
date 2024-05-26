<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoGasto extends Model
{
    protected $table = 'producto_gasto';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = ['MovimientoID', 'ProductoID', 'ImpuestoID', 'Cantidad_Productos', 'Valor_Total'];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'ProductoID');
    }

    public function gasto()
    {
        return $this->belongsTo(Gasto::class, 'VentaID');
    }
}
