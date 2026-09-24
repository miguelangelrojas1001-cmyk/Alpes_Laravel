# 📊 13. Módulo de Administración de Reportes y Métricas

## 1. `AdminReportesController.php`

### Propósito
Proporcionar un centro de inteligencia de negocios (BI) para la heladería, consolidando estadísticas financieras, ventas históricas, insumos más consumidos y rendimiento de métodos de pago dentro de rangos de fechas personalizables.

---

## 2. Métricas y Consultas Generadas

### A. Filtros por Rango de Fechas
Por defecto, consulta desde el primer día del mes actual (`date('Y-m-01')`) hasta el día de hoy (`date('Y-m-d')`):
```php
$fechaDesde = $request->input('desde', date('Y-m-01'));
$fechaHasta = $request->input('hasta', date('Y-m-d'));
```

### B. Indicadores Clave de Desempeño (KPIs)
1. **Total Ingresos Facturados:** Suma de `totalfactura` en el rango de fechas.
2. **Total Pedidos:** Conteo de pedidos y desglose porcentual por estado (`completado`, `en proceso`, `pendiente`, `cancelado`).
3. **Ticket Promedio:** Valor promedio de compra por cliente.
4. **Clientes Activos:** Total de clientes únicos que realizaron pedidos.

### C. Desgloses Estadísticos
* **Top 5 Productos Más Vendidos:** Agrupación por producto con cantidad total de unidades despachadas e ingresos generados.
* **Ventas Diarias:** Histórico día por día para graficar tendencias de demanda.
* **Distribución por Métodos de Pago:** Porcentaje y valor total recaudado por Nequi, Daviplata y Tarjetas.
* **Historial de Movimientos de Insumos:** Entradas y salidas de almacén detalladas con proveedor y fecha.
* **Historial de Alertas de Stock:** Notificaciones emitidas por el sistema en el período.
