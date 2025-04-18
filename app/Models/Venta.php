<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'venta';

    protected $primaryKey = 'ID';

    public $timestamps = true;

    protected $fillable = [
        'UsuarioID',
        'Descripcion',
        'Fecha_Venta'
    ];

    public function usuario()
    {
        return $this->belongsTo('App\Models\User', 'UsuarioID');
    }
    public function productos()
    {
        return $this->hasMany(ProductoVenta::class, 'VentaID');
    }
}
