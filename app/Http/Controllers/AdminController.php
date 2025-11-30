<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Vendedor;
use App\Models\Producto;
use App\Models\Pedido;

class AdminController extends Controller
{
    public function index()
    {
        $totalProductos  = Producto::count();
        $totalClientes   = Cliente::count();
        $totalVendedores = Vendedor::count();
        $totalPedidos    = Pedido::count();

        // Total vendido (suma de total en pedidos)
        $montoTotalVentas = Pedido::sum('total');

        // Últimos 5 pedidos
        $ultimosPedidos = Pedido::with(['cliente', 'vendedor', 'estado'])
            ->orderBy('fecha', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalProductos',
            'totalClientes',
            'totalVendedores',
            'totalPedidos',
            'montoTotalVentas',
            'ultimosPedidos'
        ));
    }
}
