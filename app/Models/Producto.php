<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producto';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'UsuarioID',
        'Nombre',
        'Cantidad',
        'Tipo',
        'Clasificacion',
        'Descripcion'
    ];

    protected $casts = [
        'Clasificacion' => 'array'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'UsuarioID', 'UsuarioID');
    }
    public function ventas()
    {
        return $this->hasMany(ProductoVenta::class, 'ProductoID');
    }
}
