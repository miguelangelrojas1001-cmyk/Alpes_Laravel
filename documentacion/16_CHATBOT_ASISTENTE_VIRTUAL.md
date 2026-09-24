# 🤖 16. Chatbot y Asistente Virtual (`ChatbotController.php`)

## 1. Propósito
El asistente virtual de **Los Alpes** brinda soporte interactivo en tiempo real a los clientes, ayudándoles a consultar productos, conocer promociones, resolver dudas sobre métodos de pago y recibir recomendaciones de sabores y combinaciones.

---

## 2. Arquitectura del Chatbot

```
[ Usuario (Chat Widget en Vista) ]
               │
               ▼ (POST /chatbot/responder vía AJAX / Fetch)
      [ ChatbotController.php ]
               │
       ┌───────┴──────────────────────────┐
       ▼                                  ▼
[ Motor de Procesamiento NLP / Reglas ] [ Consulta de Catálogo & Productos ]
       │                                  │
       └───────────────┬──────────────────┘
                       ▼
         [ Respuesta JSON Estructurada ]
```

---

## 3. Capacidades y Respuestas del Asistente

### A. Intenciones Reconocidas
1. **Catálogo y Recomendaciones:** Preguntas sobre helados populares, copas especiales, yogur con toppings y malteadas.
2. **Métodos de Pago:** Explicación sobre pagos mediante Nequi, Daviplata y Tarjetas de crédito/débito.
3. **Tiempos de Entrega y Cobertura:** Información sobre tiempos estimados (30 a 45 min) y cobertura en Campoalegre.
4. **Estado de Pedidos:** Asistencia para consultar el estado de compras activas.
5. **Horarios de Atención:** Días y horarios de apertura de la heladería.

### B. Formato de Respuesta
El endpoint retorna un objeto JSON con el mensaje y sugerencias rápidas de botones para interacción continua:
```json
{
  "respuesta": "¡Hola! Con gusto te recomiendo nuestro Helado Moon Ice con toppings de frutas frescas y salsa de chocolate.",
  "sugerencias": ["Ver catálogo", "¿Qué métodos de pago tienen?", "¿Cuánto tarda el domicilio?"]
}
```
