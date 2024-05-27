<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Venta;
use App\Models\Gasto;
use App\Models\Producto;
use App\Models\ProductoGasto;
use App\Models\ProductoVenta;

class DashboardController extends Controller
{
    public function index()
    {
        // Obtener el ID del usuario actual (reemplaza esto con tu lógica de autenticación)
        $usuarioID = Auth::id();

        // Calcular los ingresos
        $ventaIDs = Venta::where('UsuarioID', $usuarioID)->pluck('ID');
        $productoVentaIDs = ProductoVenta::whereIn('VentaID', $ventaIDs)->pluck('ID');

        // sumar todos los ProductoVenta que estan en productoVentaIDs, sabiendo el ProductoVenta[id].Cantidad_Total y ProductoVenta[id].ProductoID para hallar el Producto.Precio
        $ingresos = ProductoVenta::whereIn('ID', $productoVentaIDs)->sum( \DB::raw('Cantidad_Productos * (SELECT Precio FROM producto WHERE producto.ID = ProductoID)') );

        // Calcular los gastos (igual que las ventas de arriba, pero para los gastos)
        $gastoIDs = Gasto::where('UsuarioID', $usuarioID)->pluck('ID');
        $productoGastoIDs = ProductoGasto::whereIn('MovimientoID', $gastoIDs)->pluck('ID');

        $gastos = ProductoGasto::whereIn('ID', $productoGastoIDs)->sum( \DB::raw('Cantidad_Productos * (SELECT Precio FROM producto WHERE producto.ID = ProductoID)') );

        // Calcular el balance
        $balance = bcsub($ingresos, $gastos);

        // Calcular la cantidad de transacciones
        $cantTransacciones = ProductoVenta::whereIn('ID', $productoVentaIDs)->count() + ProductoGasto::whereIn('ID', $productoGastoIDs)->count();

        // Hallar suma de ventas por día (tomar en cuenta que la fecha esta dentro de los registros de la tabla 'Venta' (obtener por ventaIDs) donde esta fecha debera agrupar todos sus ProductoVenta por los ventaIDs que se encuentren en ese rango del dia, y asi con todos. El formato debera ser ['Ago/11' => 15220.00 (Total del dia)], y al mismo tiempo ya sabemos los productoventa gracias a la variable $productoVentaIDs)
        // Version 1:
        // $ventasPorDia = Venta::whereIn('ID', $ventaIDs)->get()->groupBy('Fecha_Venta')->map(function($ventas) use ($productoVentaIDs) {
        //     return $ventas->map(function($venta) use ($productoVentaIDs) {
        //         return ProductoVenta::whereIn('ID', $productoVentaIDs)->where('VentaID', $venta->ID)->sum( \DB::raw('Cantidad_Productos * (SELECT Precio FROM producto WHERE producto.ID = ProductoID)') );
        //     })->sum();
        // });
        
        // Version 2:
        $ventasPorDia = Venta::whereIn('ID', $ventaIDs)
            ->get()
            ->groupBy(function ($venta) {
                return \Carbon\Carbon::parse($venta->Fecha_Venta)->format('M/d');
            })
            ->map(function ($ventas) use ($productoVentaIDs) {
                return $ventas->map(function ($venta) use ($productoVentaIDs) {
                    return ProductoVenta::whereIn('ID', $productoVentaIDs)
                        ->where('VentaID', $venta->ID)
                        ->sum(\DB::raw('(Cantidad_Productos * (SELECT Precio FROM producto WHERE producto.ID = ProductoID))'));
                })->sum();
            });

        // Formatear las claves del arreglo asociativo
        $ventasPorDiaFormatted = $ventasPorDia->mapWithKeys(function ($value, $key) {
            $date = \Carbon\Carbon::createFromFormat('M/d', $key);
            return [$date->format('M/d') => $value];
        });

        // Hacer la misma version 2 pero para gastos
        $gastosPorDia = Gasto::whereIn('ID', $gastoIDs)
            ->get()
            ->groupBy(function ($gasto) {
                return \Carbon\Carbon::parse($gasto->Fecha_Gasto)->format('M/d');
            })
            ->map(function ($gastos) use ($productoGastoIDs) {
                return $gastos->map(function ($gasto) use ($productoGastoIDs) {
                    return ProductoGasto::whereIn('ID', $productoGastoIDs)
                        ->where('MovimientoID', $gasto->ID)
                        ->sum(\DB::raw('(Cantidad_Productos * (SELECT Precio FROM producto WHERE producto.ID = ProductoID))'));
                })->sum();
            });
        
        // Formatear las claves del arreglo asociativo
        $gastosPorDiaFormatted = $gastosPorDia->mapWithKeys(function ($value, $key) {
            $date = \Carbon\Carbon::createFromFormat('M/d', $key);
            return [$date->format('M/d') => $value];
        });

        // Obtener balance por dia (restar ventasPorDia - gastosPorDia)
        $balancePorDia = $ventasPorDiaFormatted->mapWithKeys(function ($value, $key) use ($gastosPorDiaFormatted) {
            return [$key => $value - $gastosPorDiaFormatted[$key]];
        });

        // dd($ventasPorDiaFormatted, $gastosPorDiaFormatted, $balancePorDia, $ingresos, $gastos, $balance, $cantTransacciones);

        return view('dashboard', compact('ingresos', 'gastos', 'gastosPorDiaFormatted', 'ventasPorDiaFormatted', 'balance', 'cantTransacciones', 'ventasPorDia', 'gastosPorDia', 'balancePorDia'));
    }
}