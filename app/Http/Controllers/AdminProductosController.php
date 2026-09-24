<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminProductosController extends Controller
{
    /**
     * Display admin products and recipes management dashboard.
     */
    public function index(Request $request)
    {
        $productoEditar  = null;
        $insumosProducto = [];

        if ($request->has('editar')) {
            $idEditar = (int)$request->get('editar');
            $productoEditar = DB::table('producto')->where('idproducto', $idEditar)->first();

            if ($productoEditar) {
                $productoEditar = (array)$productoEditar;
                $rawInsumos = DB::table('detalle_insumo')
                    ->where('idproducto', $idEditar)
                    ->get();
                foreach ($rawInsumos as $ri) {
                    $insumosProducto[$ri->idinsumo] = $ri->cantidad;
                }
            }
        }

        $productosList = DB::table('producto as p')
            ->leftJoin('catalogo as c', 'p.idcatalogo', '=', 'c.idcatalogo')
            ->select('p.*', 'c.nombre as catalogo')
            ->orderBy('p.idproducto', 'desc')
            ->get();

        $productos = $productosList->map(fn($item) => (array)$item)->toArray();

        $catalogos = DB::table('catalogo')->orderBy('nombre', 'asc')->get()->map(fn($item) => (array)$item)->toArray();

        $insumosList = DB::table('insumo')
            ->orderBy('categoria', 'asc')
            ->orderBy('nombre', 'asc')
            ->get()
            ->map(fn($item) => (array)$item)
            ->toArray();

        // Agrupar insumos por categoría normalizando nombres
        $insumosGrouped = [];
        $ordenDeseado = [
            'Sabores de Helado',
            'Toppings Dulces',
            'Toppings Frutas',
            'Toppings Premium',
            'Salsas y Untables',
            'Waffles',
            'Canastas',
            'General'
        ];

        foreach ($insumosList as $ins) {
            $cat = trim($ins['categoria'] ?? '');
            if ($cat === 'Base Waffles') $cat = 'Waffles';
            if ($cat === 'Base Canastas') $cat = 'Canastas';
            if (empty($cat)) $cat = 'General';
            $ins['categoria'] = $cat;
            $insumosGrouped[$cat][] = $ins;
        }

        // Ordenar según el orden deseado
        uksort($insumosGrouped, function($a, $b) use ($ordenDeseado) {
            $posA = array_search($a, $ordenDeseado);
            $posB = array_search($b, $ordenDeseado);
            if ($posA === false) $posA = 999;
            if ($posB === false) $posB = 999;
            return $posA <=> $posB;
        });

        return view('dashboard.admin.productos', compact(
            'productos',
            'catalogos',
            'insumosList',
            'insumosGrouped',
            'productoEditar',
            'insumosProducto'
        ));
    }

    /**
     * Create or update product and recipe details within a transaction.
     */
    public function guardar(Request $request)
    {
        $idproducto  = (int)$request->input('_editar', 0);
        $idcatalogo  = $request->input('idcatalogo') ? (int)$request->input('idcatalogo') : null;
        $nombre      = trim($request->input('nombre', ''));
        $descripcion = trim($request->input('descripcion', ''));
        $precio      = (float)$request->input('precio', 0);
        $disponible  = $request->has('disponible') ? 1 : 0;
        $insumos     = $request->input('insumos', []);

        if (empty($nombre)) {
            return redirect()->route('admin.productos.index');
        }

        $imagenPath = null;
        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            $file = $request->file('imagen');
            $nombreImagen = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $nombreImagen);
            $imagenPath = 'img/' . $nombreImagen;
        }

        try {
            DB::transaction(function () use ($idproducto, $idcatalogo, $nombre, $descripcion, $precio, $disponible, $imagenPath, $insumos, &$mensajeSuccess) {
                if ($idproducto > 0) {
                    // Actualización
                    $dataUpdate = [
                        'idcatalogo'  => $idcatalogo,
                        'nombre'      => $nombre,
                        'descripcion' => $descripcion,
                        'precio'      => $precio,
                        'disponible'  => $disponible,
                    ];
                    if ($imagenPath) {
                        $dataUpdate['imagen'] = $imagenPath;
                    }

                    DB::table('producto')
                        ->where('idproducto', $idproducto)
                        ->update($dataUpdate);

                    // Reemplazar receta en detalle_insumo
                    DB::table('detalle_insumo')->where('idproducto', $idproducto)->delete();

                    foreach ($insumos as $idinsumo => $cantidad) {
                        $cant = (float)$cantidad;
                        if ($cant > 0) {
                            DB::table('detalle_insumo')->insert([
                                'idproducto' => $idproducto,
                                'idinsumo'   => (int)$idinsumo,
                                'cantidad'   => $cant,
                            ]);
                        }
                    }
                    $mensajeSuccess = 'Producto actualizado correctamente.';
                } else {
                    // Creación
                    $newId = DB::table('producto')->insertGetId([
                        'idcatalogo'  => $idcatalogo,
                        'nombre'      => $nombre,
                        'descripcion' => $descripcion,
                        'precio'      => $precio,
                        'imagen'      => $imagenPath ?? '',
                        'disponible'  => $disponible,
                    ]);

                    foreach ($insumos as $idinsumo => $cantidad) {
                        $cant = (float)$cantidad;
                        if ($cant > 0) {
                            DB::table('detalle_insumo')->insert([
                                'idproducto' => $newId,
                                'idinsumo'   => (int)$idinsumo,
                                'cantidad'   => $cant,
                            ]);
                        }
                    }
                    $mensajeSuccess = 'Producto agregado correctamente.';
                }
            });

            return redirect()->route('admin.productos.index')->with('mensaje', $mensajeSuccess);
        } catch (\Exception $e) {
            Log::error("[AdminProductos] Error al guardar producto: " . $e->getMessage());
            $errMsg = ($idproducto > 0) ? 'Error al actualizar el producto.' : 'Error al agregar el producto.';
            return redirect()->route('admin.productos.index')->with('mensaje', $errMsg);
        }
    }

    /**
     * Delete product. Respects purchase history.
     */
    public function eliminar(Request $request, $id)
    {
        $idproducto = (int)$id;

        if ($idproducto > 0) {
            try {
                DB::transaction(function () use ($idproducto) {
                    DB::table('detalle_insumo')->where('idproducto', $idproducto)->delete();
                    DB::table('producto')->where('idproducto', $idproducto)->delete();
                });
                return redirect()->route('admin.productos.index');
            } catch (\Exception $e) {
                Log::warning("[AdminProductos] No se pudo eliminar producto {$idproducto}: " . $e->getMessage());
                return redirect()->route('admin.productos.index')->with('mensaje', 'Error al eliminar el producto.');
            }
        }

        return redirect()->route('admin.productos.index');
    }
}
