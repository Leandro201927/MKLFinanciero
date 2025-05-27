<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Venta;
use App\Models\Gasto;
use App\Models\Producto;
use App\Models\ProductoGasto;
use App\Models\ProductoVenta;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener el ID del usuario actual
        $usuarioID = Auth::id();

        // Obtener las ventas y gastos del usuario
        $ventaIDs = Venta::where('UsuarioID', $usuarioID)->pluck('ID');
        $gastoIDs = Gasto::where('UsuarioID', $usuarioID)->pluck('ID');

        // Calcular los ingresos usando Valor_Total
        $ingresos = ProductoVenta::whereIn('VentaID', $ventaIDs)->sum('Valor_Total');

        // Calcular los gastos usando Valor_Total
        $gastos = ProductoGasto::whereIn('MovimientoID', $gastoIDs)->sum('Valor_Total');

        // Calcular el balance
        $balance = bcsub($ingresos, $gastos);

        // Calcular la cantidad de transacciones (solo del usuario actual)
        $cantTransacciones = ProductoVenta::whereIn('VentaID', $ventaIDs)->count() + 
                            ProductoGasto::whereIn('MovimientoID', $gastoIDs)->count();

        // Obtener últimos registros (ventas y gastos del usuario)
        $ultimasVentas = Venta::where('UsuarioID', $usuarioID)
            ->select('ID', 'Descripcion', 'Fecha_Venta as Fecha', 'Codigo', DB::raw("'Venta' as Tipo"))
            ->get();

        $ultimosGastos = Gasto::where('UsuarioID', $usuarioID)
            ->select('ID', 'Descripcion', 'Fecha_Gasto as Fecha', 'Codigo', DB::raw("'Gasto' as Tipo"))
            ->get();

        // Combinar y ordenar las transacciones
        $ultimosRegistros = $ultimasVentas->concat($ultimosGastos)
            ->sortByDesc('Fecha')
            ->take(5)
            ->values();

        // Obtener ventas por día
        $ventasPorDia = Venta::whereIn('ID', $ventaIDs)
            ->orderBy('Fecha_Venta', 'asc')
            ->get()
            ->groupBy(function ($venta) {
                return \Carbon\Carbon::parse($venta->Fecha_Venta)->format('M/d');
            })
            ->map(function ($ventas) {
                return $ventas->map(function ($venta) {
                    return ProductoVenta::where('VentaID', $venta->ID)
                        ->sum('Valor_Total');
                })->sum();
            });

        // Formatear las claves del arreglo asociativo de ventas
        $ventasPorDiaFormatted = $ventasPorDia->mapWithKeys(function ($value, $key) {
            $date = \Carbon\Carbon::createFromFormat('M/d', $key);
            return [$date->format('M/d') => $value];
        });

        // Obtener gastos por día
        $gastosPorDia = Gasto::whereIn('ID', $gastoIDs)
            ->orderBy('Fecha_Gasto', 'asc')
            ->get()
            ->groupBy(function ($gasto) {
                return \Carbon\Carbon::parse($gasto->Fecha_Gasto)->format('M/d');
            })
            ->map(function ($gastos) {
                return $gastos->map(function ($gasto) {
                    return ProductoGasto::where('MovimientoID', $gasto->ID)
                        ->sum('Valor_Total');
                })->sum();
            });
        
        // Formatear las claves del arreglo asociativo de gastos
        $gastosPorDiaFormatted = $gastosPorDia->mapWithKeys(function ($value, $key) {
            $date = \Carbon\Carbon::createFromFormat('M/d', $key);
            return [$date->format('M/d') => $value];
        });

        // Obtener todas las fechas en el rango completo
        $fechasCompletas = collect($ventasPorDiaFormatted)
            ->merge($gastosPorDiaFormatted)
            ->keys()
            ->unique();

        // Sincronizar las ventas vs gastos por dia
        $ventasPorDiaCompleto = $fechasCompletas->mapWithKeys(function ($fecha) use ($ventasPorDiaFormatted) {
            return [$fecha => $ventasPorDiaFormatted[$fecha] ?? 0];
        });

        $gastosPorDiaCompleto = $fechasCompletas->mapWithKeys(function ($fecha) use ($gastosPorDiaFormatted) {
            return [$fecha => $gastosPorDiaFormatted[$fecha] ?? 0];
        });

        // Obtener balance por día (últimos 10 días)
        $balancePorDiaCompleto = $ventasPorDiaCompleto->mapWithKeys(function ($value, $key) use ($gastosPorDiaCompleto) {
            return [$key => $value - $gastosPorDiaCompleto[$key]];
        })->take(-10);

        return view('dashboard', compact(
            'balance', 
            'ingresos', 
            'gastos', 
            'ultimosRegistros', 
            'gastosPorDiaFormatted', 
            'ventasPorDiaFormatted', 
            'cantTransacciones', 
            'ventasPorDia', 
            'gastosPorDia', 
            'ventasPorDiaCompleto', 
            'gastosPorDiaCompleto', 
            'balancePorDiaCompleto'
        ));
    }
}