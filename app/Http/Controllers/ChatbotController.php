<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    /**
     * Handle incoming chatbot message and return JSON response.
     */
    public function responder(Request $request)
    {
        $mensaje = trim($request->input('mensaje', ''));

        if (empty($mensaje)) {
            return response()->json(['respuesta' => 'Escribe tu pregunta y te ayudo.']);
        }

        $user = Auth::user();
        $rol = strtolower($user->rol_nombre ?? 'cliente');
        $nombre = $user->nombre ?? 'amigo';

        $respuesta = self::procesar($mensaje, $rol, $nombre);

        return response()->json(['respuesta' => $respuesta]);
    }

    /**
     * Normalize text removing accents and special punctuation.
     */
    private static function normalizar(string $texto): string
    {
        $texto = mb_strtolower($texto, 'UTF-8');
        $remplazos = [
            'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u', 'ü'=>'u', 'ñ'=>'n',
            '¿'=>'', '?'=>'', '¡'=>'', '!'=>'', ','=>'', '.'=>'', ';'=>''
        ];
        return strtr($texto, $remplazos);
    }

    /**
     * Process message based on keywords and user role.
     */
    private static function procesar(string $rawMsg, string $rol, string $nombre): string
    {
        $msg = self::normalizar($rawMsg);

        // ── 1. Saludos ───────────────────────────────────────────────────
        if (self::contiene($msg, ['hola', 'buenas', 'buenos', 'hey', 'saludos', 'hi'])) {
            $primerNombre = explode(' ', trim($nombre))[0];
            return "¡Hola, {$primerNombre}! Soy el asistente de Los Alpes. ¿En qué te puedo ayudar hoy?";
        }

        // ── 2. Admin Específicos (Evaluar antes para admin) ──────────────
        if ($rol === 'administrador') {
            if (self::contiene($msg, ['agregar producto', 'nuevo producto', 'crear producto', 'como agrego'])) {
                return "En <strong>Productos</strong> en el menú lateral puedes agregar productos, subir imágenes, asignar precio y construir la receta asignando insumos necesarios.";
            }
            if (self::contiene($msg, ['inventario', 'stock', 'insumos', 'insumo'])) {
                return "En <strong>Inventario</strong> puedes registrar entradas/salidas de insumos, ajustar el stock actual y ver alertas de stock crítico.";
            }
            if (self::contiene($msg, ['cambio el estado', 'ventas', 'pedidos admin'])) {
                return "En <strong>Ventas</strong> puedes ver todos los pedidos, cambiar su estado (pendiente → en proceso → completado) y ver a qué domiciliario están asignados.";
            }
        }

        // ── 3. Domiciliario Específicos ──────────────────────────────────
        if ($rol === 'domiciliario') {
            if (self::contiene($msg, ['entrega', 'entregado', 'camino', 'marcar'])) {
                return "En tu panel de <strong>Envíos</strong> haz clic en 'Marcar en camino' cuando salgas, y luego en 'Confirmar entrega' al llegar con el cliente.";
            }
        }

        // ── 4. Guía de Pedidos Específica ────────────────────────────────
        if (self::contiene($msg, ['como hago', 'como hacer', 'como pedir', 'como comprar', 'como realizo', 'quiero pedir', 'quiero comprar', 'hacer un pedido', 'realizar un pedido', 'proceso de compra'])) {
            return "Para hacer un pedido:\n1. Ve al <strong>Catálogo</strong> y elige una categoría\n2. Selecciona el producto que quieres\n3. Personalízalo (sabores, toppings, etc.)\n4. Haz clic en <strong>Agregar al carrito</strong> o <strong>Pedir ahora</strong>\n5. Completa los datos de entrega\n6. Elige el método de pago\n7. ¡Listo! Recibirás tu pedido en 30-45 minutos.";
        }

        // ── 5. Ver estado de pedidos ─────────────────────────────────────
        if (self::contiene($msg, ['ver mis pedidos', 'estado del pedido', 'estado de mi pedido', 'mis pedidos', 'historial de pedidos', 'donde esta mi pedido', 'seguimiento'])) {
            if ($rol === 'cliente') {
                return "Puedes ver el estado de tus pedidos en <strong>Mis Pedidos</strong> en el menú lateral. Ahí verás si están pendientes, en proceso o completados, y podrás imprimir la factura de cada uno.";
            }
        }

        // ── 6. Carrito ───────────────────────────────────────────────────
        if (self::contiene($msg, ['carrito', 'agregar al carrito', 'anadir al carrito'])) {
            return "Para agregar productos al carrito:\n1. Ve al catálogo y selecciona una categoría\n2. Haz clic en <strong>Personalizar y pedir</strong>\n3. Elige tus opciones y haz clic en <strong>Agregar al carrito</strong>\n4. Sigue agregando más productos\n5. Cuando termines, ve al carrito y haz clic en <strong>Proceder al pago</strong>.";
        }

        // ── 7. Pago ──────────────────────────────────────────────────────
        if (self::contiene($msg, ['pago', 'pagar', 'metodo', 'nequi', 'daviplata', 'tarjeta'])) {
            return "Aceptamos los siguientes métodos de pago:\n• <strong>Nequi</strong>\n• <strong>Daviplata</strong>\n• <strong>Tarjeta crédito/débito</strong> (Visa, Mastercard, Amex)\n\nNo aceptamos pago en efectivo.";
        }

        // ── 8. Domicilio ─────────────────────────────────────────────────
        if (self::contiene($msg, ['domicilio', 'envio', 'entrega', 'despacho', 'llega', 'tiempo'])) {
            return "El domicilio tiene un costo de <strong>$2.000</strong> y el tiempo estimado de entrega es de <strong>30 a 45 minutos</strong>. Solo hacemos entregas en <strong>Campoalegre</strong>.";
        }

        // ── 9. Ciudades ──────────────────────────────────────────────────
        if (self::contiene($msg, ['ciudad', 'campoalegre', 'zona', 'cobertura', 'donde'])) {
            return "Hacemos entregas únicamente en <strong>Campoalegre</strong>. Si estás en otra ciudad, por ahora no tenemos cobertura.";
        }

        // ── 10. Productos Específicos por Tipo ───────────────────────────
        if (self::contiene($msg, ['waffle', 'wafle', 'waffles'])) {
            return "Tenemos waffles artesanales belgas:\n• <strong>Waffle Entero</strong> ($23.000) — 4 toppings + helado\n• <strong>Waffle Medio</strong> ($16.000) — 2 toppings + helado\n• Waffles especiales: Oreo, Nutella, Hersheys, Jet, Kinder, Nucita y más\n• Adición bola de helado extra: <strong>$4.000</strong>\n\nTodos incluyen untable y sabor de helado a tu elección.";
        }

        if (self::contiene($msg, ['malteada', 'malteadas', 'shake'])) {
            return "Nuestras malteadas:\n• <strong>Malteada Sencilla</strong> — $13.000, elige 1 sabor de 22 opciones\n• <strong>Malteadas especiales</strong>: Baileys, Nutella, Yogurt ($15.000), Kiwi\n\nSabores disponibles: Oreo, Frutos rojos, Chocolate, Arequipe, Chicle, Maracuyá, Tres leches, Unicornio, Lulo, Acid Mix y muchos más.";
        }

        if (self::contiene($msg, ['yogurt', 'yogur', 'helado de yogurt', 'moon ice', 'ice fit'])) {
            return "Nuestro helado de yogurt es bajo en grasa y azúcar. Algunos productos destacados:\n• <strong>Moon Ice</strong> ($17.000) — base + 4 acompañamientos\n• <strong>Ice Fit</strong> ($18.000) — con arándanos, granola, fresa y nueces\n• <strong>7 Deseos</strong> ($26.000) — 7 acompañamientos\n\nPuedes agregar toppings de frutas ($2.500), premium ($3.000) y salsas.";
        }

        if (self::contiene($msg, ['canasta', 'canastas'])) {
            return "Nuestras canastas de galleta crujiente:\n• <strong>Canasta Sencilla</strong> ($12.000) — 1 bola de helado + 2 toppings\n• <strong>Canasta Doble</strong> ($18.000) — 2 bolas de helado + 3 toppings\n• <strong>Canasta Especial</strong> ($22.000) — 3 bolas + 4 toppings + salsa";
        }

        // ── 11. Productos General ────────────────────────────────────────
        if (self::contiene($msg, ['producto', 'productos', 'categoria', 'categorias', 'menu', 'catalogo', 'que tienen', 'que venden', 'que hay'])) {
            return "Tenemos estas categorías:\n• <strong>Helado de Yogurt</strong> — bajo en grasa y azúcar\n• <strong>Ensaladas de Frutas</strong> — Mini, Especial, Super Especial\n• <strong>Malteadas</strong> — más de 20 sabores\n• <strong>Canastas</strong> — arma la tuya con toppings\n• <strong>Waffles</strong> — clásicos y especiales\n\nVe al catálogo para ver todos los productos.";
        }

        // ── 12. Pedidos General ──────────────────────────────────────────
        if (self::contiene($msg, ['pedido', 'pedidos', 'orden', 'compra'])) {
            if ($rol === 'cliente') {
                return "¿Qué necesitas saber sobre pedidos?\n• Para <strong>hacer un pedido</strong>: ve al catálogo y selecciona un producto\n• Para <strong>ver tus pedidos</strong>: haz clic en <strong>Mis Pedidos</strong> en el menú\n• Para <strong>imprimir una factura</strong>: abre el pedido en Mis Pedidos y haz clic en Imprimir";
            }
        }

        // ── 13. Respuesta por defecto ────────────────────────────────────
        return "No entendí muy bien tu pregunta. Puedes preguntarme sobre:\n• <strong>Cómo hacer un pedido</strong>\n• <strong>Métodos de pago</strong>\n• <strong>Costo del domicilio</strong>\n• <strong>Productos, waffles o malteadas</strong>";
    }

    private static function contiene(string $texto, array $palabras): bool
    {
        foreach ($palabras as $p) {
            if (strpos($texto, $p) !== false) {
                return true;
            }
        }
        return false;
    }
}
