<?php

use App\Http\Controllers\AdminCategoriasController;
use App\Http\Controllers\AdminInventarioController;
use App\Http\Controllers\AdminProductosController;
use App\Http\Controllers\AdminProveedoresController;
use App\Http\Controllers\AdminReportesController;
use App\Http\Controllers\AdminUsuariosController;
use App\Http\Controllers\AdminVentasController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ClienteDashboardController;
use App\Http\Controllers\DomiciliarioDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Catálogo público accesible para todos los roles (Clientes, Domiciliarios, Admin, Invitados)
Route::get('/', [CatalogoController::class, 'index'])->name('home');
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');

// Rutas de Autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [LoginController::class, 'register'])->name('register.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas del Carrito (Sesión)
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::post('/carrito/actualizar', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::delete('/carrito/eliminar/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/vaciar', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

// Rutas de Personalización (Step 1 Checkout)
Route::get('/checkout/carrito', [CheckoutController::class, 'iniciarCarrito'])->name('checkout.iniciarCarrito');
Route::get('/checkout/opciones', [CheckoutController::class, 'opciones'])->name('checkout.opciones');
Route::post('/checkout/opciones', [CheckoutController::class, 'guardarOpciones'])->name('checkout.guardarOpciones');

// Rutas Protegidas que requieren estar Autenticado (Cliente, Admin o Domiciliario)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/entrega', [CheckoutController::class, 'entrega'])->name('checkout.entrega');
    Route::post('/checkout/entrega', [CheckoutController::class, 'guardarEntrega'])->name('checkout.guardarEntrega');
    Route::get('/checkout/pago', [CheckoutController::class, 'pago'])->name('checkout.pago');
    Route::post('/checkout/confirmar', [CheckoutController::class, 'confirmar'])->name('checkout.confirmar');
    Route::get('/checkout/factura', [CheckoutController::class, 'factura'])->name('checkout.factura');

    // Chatbot Asistente Virtual
    Route::post('/chatbot/responder', [ChatbotController::class, 'responder'])->name('chatbot.responder');
});

// Rutas Protegidas por Rol: Administrador
Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return redirect()->route('admin.productos.index');
    })->name('admin.dashboard');

    // Ventas y Pedidos Admin
    Route::get('/admin/ventas', [AdminVentasController::class, 'index'])->name('admin.ventas.index');
    Route::post('/admin/ventas/estado', [AdminVentasController::class, 'actualizarEstado'])->name('admin.ventas.estado');

    // Inventario e Insumos Admin
    Route::get('/admin/inventario', [AdminInventarioController::class, 'index'])->name('admin.inventario.index');
    Route::post('/admin/inventario/insumo', [AdminInventarioController::class, 'guardarInsumo'])->name('admin.inventario.insumo.guardar');
    Route::post('/admin/inventario/movimiento', [AdminInventarioController::class, 'registrarMovimiento'])->name('admin.inventario.movimiento');
    Route::delete('/admin/inventario/insumo/{id}', [AdminInventarioController::class, 'eliminarInsumo'])->name('admin.inventario.insumo.eliminar');

    // Productos y Recetas Admin
    Route::get('/admin/productos', [AdminProductosController::class, 'index'])->name('admin.productos.index');
    Route::post('/admin/productos', [AdminProductosController::class, 'guardar'])->name('admin.productos.guardar');
    Route::delete('/admin/productos/{id}', [AdminProductosController::class, 'eliminar'])->name('admin.productos.eliminar');

    // Proveedores Admin
    Route::get('/admin/proveedores', [AdminProveedoresController::class, 'index'])->name('admin.proveedores.index');
    Route::post('/admin/proveedores', [AdminProveedoresController::class, 'guardar'])->name('admin.proveedores.guardar');
    Route::delete('/admin/proveedores/{id}', [AdminProveedoresController::class, 'eliminar'])->name('admin.proveedores.eliminar');

    // Categorías / Catálogos Admin
    Route::get('/admin/categorias', [AdminCategoriasController::class, 'index'])->name('admin.categorias.index');
    Route::post('/admin/categorias', [AdminCategoriasController::class, 'guardar'])->name('admin.categorias.guardar');
    Route::post('/admin/categorias/{id}/toggle', [AdminCategoriasController::class, 'toggle'])->name('admin.categorias.toggle');
    Route::delete('/admin/categorias/{id}', [AdminCategoriasController::class, 'eliminar'])->name('admin.categorias.eliminar');

    // Usuarios y Roles Admin
    Route::get('/admin/usuarios', [AdminUsuariosController::class, 'index'])->name('admin.usuarios.index');
    Route::post('/admin/usuarios/rol', [AdminUsuariosController::class, 'cambiarRol'])->name('admin.usuarios.cambiarRol');
    Route::post('/admin/usuarios', [AdminUsuariosController::class, 'crear'])->name('admin.usuarios.crear');
    Route::delete('/admin/usuarios/{id}', [AdminUsuariosController::class, 'eliminar'])->name('admin.usuarios.eliminar');

    // Reportes y Análisis Admin
    Route::get('/admin/reportes', [AdminReportesController::class, 'index'])->name('admin.reportes.index');
});

// Rutas Protegidas por Rol: Cliente
Route::middleware(['auth', 'role:cliente'])->group(function () {
    Route::get('/cliente/dashboard', [ClienteDashboardController::class, 'index'])->name('cliente.dashboard');
});

// Rutas Protegidas por Rol: Domiciliario
Route::middleware(['auth', 'role:domiciliario'])->group(function () {
    Route::get('/domiciliario/dashboard', [DomiciliarioDashboardController::class, 'index'])->name('domiciliario.dashboard');
    Route::post('/domiciliario/pedidos/estado', [DomiciliarioDashboardController::class, 'actualizarEstado'])->name('domiciliario.actualizarEstado');
});
