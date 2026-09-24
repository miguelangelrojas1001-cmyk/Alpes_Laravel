<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminInventarioController extends Controller
{
    /**
     * Display admin inventory and insumo management dashboard.
     */
    public function index(Request $request)
    {
        $insumos = DB::table('insumo')
            ->select('idinsumo', 'nombre', 'stock_actual', 'stock_minimo', 'unidad_medida', 'categoria')
            ->orderBy('nombre')
            ->get()
            ->map(function($ins) {
                $c = trim($ins->categoria ?? '');
                if ($c === 'Base Waffles') $ins->categoria = 'Waffles';
                if ($c === 'Base Canastas') $ins->categoria = 'Canastas';
                if (empty($ins->categoria)) $ins->categoria = 'General';
                return $ins;
            });

        $movimientos = DB::table('inventario as inv')
            ->leftJoin('insumo as ins', 'inv.idinsumo', '=', 'ins.idinsumo')
            ->select('inv.*', 'ins.nombre as insumo_nombre')
            ->orderBy('inv.fecha', 'desc')
            ->limit(30)
            ->get();

        return view('dashboard.admin.inventario', compact('insumos', 'movimientos'));
    }

    /**
     * Create or update insumo details.
     */
    public function guardarInsumo(Request $request)
    {
        $idinsumo = (int)$request->input('idinsumo', 0);
        $nombre   = trim($request->input('nombre', ''));
        $stock    = (float)$request->input('stock_actual', 0);
        $minimo   = (float)$request->input('stock_minimo', 0);
        $um       = $request->input('unidad_medida', 'unidad');
        $cat      = $request->input('categoria', 'General');
        if ($cat === 'Base Waffles') $cat = 'Waffles';
        if ($cat === 'Base Canastas') $cat = 'Canastas';

        if (empty($nombre)) {
            return redirect()->route('admin.inventario.index');
        }

        if ($idinsumo > 0) {
            // Editar insumo existente
            DB::table('insumo')
                ->where('idinsumo', $idinsumo)
                ->update([
                    'nombre'        => $nombre,
                    'stock_minimo'  => $minimo,
                    'unidad_medida' => $um,
                ]);
        } else {
            // Crear nuevo insumo
            DB::table('insumo')->insert([
                'nombre'        => $nombre,
                'stock_actual'  => $stock,
                'stock_minimo'  => $minimo,
                'unidad_medida' => $um,
                'categoria'     => $cat,
            ]);
        }

        return redirect()->route('admin.inventario.index');
    }

    /**
     * Register manual entry or exit of inventory stock within a transaction.
     */
    public function registrarMovimiento(Request $request)
    {
        $accion   = $request->input('accion');
        $idinsumo = (int)$request->input('idinsumo', 0);
        $cantidad = (float)$request->input('cantidad', 0);

        if ($idinsumo <= 0 || $cantidad <= 0 || !in_array($accion, ['entrada', 'salida'])) {
            return redirect()->route('admin.inventario.index');
        }

        DB::transaction(function () use ($accion, $idinsumo, $cantidad) {
            if ($accion === 'entrada') {
                DB::table('insumo')
                    ->where('idinsumo', $idinsumo)
                    ->increment('stock_actual', $cantidad);

                DB::table('inventario')->insert([
                    'idinsumo'     => $idinsumo,
                    'tipo_entrada' => 1,
                    'tipo_salida'  => 0,
                    'cantidad'     => $cantidad,
                    'fecha'        => now(),
                ]);
            } elseif ($accion === 'salida') {
                DB::table('insumo')
                    ->where('idinsumo', $idinsumo)
                    ->decrement('stock_actual', $cantidad);

                DB::table('inventario')->insert([
                    'idinsumo'     => $idinsumo,
                    'tipo_entrada' => 0,
                    'tipo_salida'  => 1,
                    'cantidad'     => $cantidad,
                    'fecha'        => now(),
                ]);
            }
        });

        return redirect()->route('admin.inventario.index');
    }

    /**
     * Delete insumo using transactional order: inventario -> detalle_insumo -> insumo.
     */
    public function eliminarInsumo(Request $request, $id)
    {
        $idinsumo = (int)$id;

        if ($idinsumo > 0) {
            try {
                DB::transaction(function () use ($idinsumo) {
                    DB::table('inventario')->where('idinsumo', $idinsumo)->delete();
                    DB::table('detalle_insumo')->where('idinsumo', $idinsumo)->delete();
                    DB::table('insumo')->where('idinsumo', $idinsumo)->delete();
                });
            } catch (\Exception $e) {
                Log::error("[AdminInventario] Error al eliminar insumo {$idinsumo}: " . $e->getMessage());
                return redirect()->route('admin.inventario.index')->with('error', 'Error al eliminar el insumo.');
            }
        }

        return redirect()->route('admin.inventario.index');
    }
}
