<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Vendedor;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        // Tarjetas principales
        $stats = [
            'productos'  => Producto::count(),
            'clientes'   => Cliente::count(),
            'vendedores' => Vendedor::count(),
            'pedidos'    => Pedido::count(),
        ];

        // Pedidos por estado (Creado, Pagado, Enviado, etc.)
        $pedidosEstadoQuery = DB::table('pedidos')
            ->join('estados_pedido', 'pedidos.estado_id', '=', 'estados_pedido.estado_id')
            ->select('estados_pedido.nombre as estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estados_pedido.nombre')
            ->orderBy('total', 'desc')
            ->get();

        // Productos por categoría
        $productosCategoriaQuery = DB::table('productos')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.categoria_id')
            ->select('categorias.nombre as categoria', DB::raw('COUNT(*) as total'))
            ->groupBy('categorias.nombre')
            ->orderBy('total', 'desc')
            ->get();

        // Top 5 clientes por número de pedidos
        $topClientes = DB::table('pedidos')
            ->join('clientes', 'pedidos.cliente_id', '=', 'clientes.cliente_id')
            ->select(
                'clientes.cliente_id',
                DB::raw("CONCAT(clientes.nombres, ' ', clientes.apellidos) as nombre"),
                DB::raw('COUNT(*) as total_pedidos'),
                DB::raw('SUM(pedidos.total) as monto_total')
            )
            ->groupBy('clientes.cliente_id', 'clientes.nombres', 'clientes.apellidos')
            ->orderByDesc('total_pedidos')
            ->limit(5)
            ->get();

        // Datos preparados para las gráficas
        $chartData = [
            'pedidosPorEstado' => [
                'labels' => $pedidosEstadoQuery->pluck('estado'),
                'values' => $pedidosEstadoQuery->pluck('total'),
            ],
            'productosPorCategoria' => [
                'labels' => $productosCategoriaQuery->pluck('categoria'),
                'values' => $productosCategoriaQuery->pluck('total'),
            ],
        ];

        $user = Auth::user();

        return view('admin.dashboard', compact('stats', 'user', 'chartData', 'topClientes'));
    }
}
