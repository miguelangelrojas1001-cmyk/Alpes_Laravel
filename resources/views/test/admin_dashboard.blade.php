<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador - Prueba Etapa 2</title>
    <style>
        body { font-family: sans-serif; padding: 2rem; background: #ebf8ff; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .badge { background: #3182ce; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold; }
        .btn-logout { background: #e53e3e; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Panel de Administrador</h1>
        <p>Bienvenido, <strong>{{ Auth::user()->nombre }}</strong> ({{ Auth::user()->correo }})</p>
        <p>Rol asignado: <span class="badge">{{ Auth::user()->rol_nombre }}</span></p>
        
        <form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem;">
            @csrf
            <button type="submit" class="btn-logout">Cerrar Sesión</button>
        </form>
    </div>
</body>
</html>
