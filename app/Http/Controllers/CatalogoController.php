<?php

namespace App\Http\Controllers;

use App\Models\Catalogo;
use App\Models\Producto;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    /**
     * Display the public catalog and products by category tab.
     */
    public function index(Request $request)
    {
        $categorias = Catalogo::orderBy('nombre')->get();

        $idActivo = (int)$request->get('idcatalogo', 0);

        if (!$idActivo && $categorias->isNotEmpty()) {
            $idActivo = (int)$categorias->first()->idcatalogo;
        }

        $catActiva = $categorias->firstWhere('idcatalogo', $idActivo);

        if (!$catActiva && $categorias->isNotEmpty()) {
            $catActiva = $categorias->first();
            $idActivo = (int)$catActiva->idcatalogo;
        }

        $productos = collect();

        if ($catActiva) {
            $productos = Producto::where('idcatalogo', $idActivo)
                ->where('disponible', 1)
                ->get();
        }

        return view('catalogo.index', compact('categorias', 'catActiva', 'productos', 'idActivo'));
    }
}
