<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;

    protected $table = 'Movimiento';

    protected $primaryKey = 'ID';

    public $timestamps = true;

    protected $fillable = [
        'UsuarioID',
        'Descripcion',
        'Fecha_Gasto'
    ];

    public function usuario()
    {
        return $this->belongsTo('App\Models\User', 'UsuarioID');
    }
    public function productos()
    {
        return $this->hasMany(ProductoGasto::class, 'MovimientoID');
    }
}
