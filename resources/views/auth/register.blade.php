<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse — Los Alpes</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --page-bg:   #F1F5F9;
            --card-bg:   #ffffff;
            --title-clr: #0F172A;
            --sub-clr:   #64748B;
            --label-clr: #334155;
            --input-bg:  #F8FAFC;
            --input-bd:  #E2E8F0;
            --input-clr: #0F172A;
            --icon-clr:  #94A3B8;
            --reg-clr:   #64748B;
        }
        [data-theme="dark"] {
            --page-bg:   #111827;
            --card-bg:   #1f2937;
            --title-clr: #f1f5f9;
            --sub-clr:   rgba(255,255,255,.45);
            --label-clr: rgba(255,255,255,.6);
            --input-bg:  rgba(255,255,255,.06);
            --input-bd:  rgba(255,255,255,.12);
            --input-clr: #f1f5f9;
            --icon-clr:  rgba(255,255,255,.35);
            --reg-clr:   rgba(255,255,255,.45);
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: var(--page-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            transition: background .25s;
        }

        .register-card {
            display: grid;
            grid-template-columns: 420px 1fr;
            width: 100%;
            max-width: 900px;
            min-height: 580px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,.2);
        }

        /* ── Panel izquierdo ── */
        .reg-left {
            background: linear-gradient(145deg, #1E3A5F 0%, #2563EB 60%, #3B82F6 100%);
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .reg-left::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: rgba(255,255,255,.06);
        }
        .reg-left::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 220px; height: 220px;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
        }

        .left-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            position: relative;
            z-index: 1;
        }
        .left-brand-icon {
            width: 46px; height: 46px;
            background: rgba(255,255,255,.15);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(4px);
        }
        .left-brand-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
        }

        .left-body {
            position: relative;
            z-index: 1;
        }
        .left-title {
            font-size: 2rem;
            font-weight: 900;
            color: #fff;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        .left-desc {
            font-size: .9rem;
            color: rgba(255,255,255,.75);
            line-height: 1.6;
        }

        .left-footer {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,.1);
            border-radius: 14px;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            backdrop-filter: blur(4px);
        }
        .left-footer-title { font-size: .88rem; font-weight: 800; color: #fff; }
        .left-footer-sub   { font-size: .75rem; color: rgba(255,255,255,.65); }

        /* ── Panel derecho ── */
        .reg-right {
            background: var(--card-bg);
            padding: 2.5rem 2.75rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: background .25s;
        }

        .right-title {
            font-size: 1.75rem;
            font-weight: 900;
            color: var(--title-clr);
            margin-bottom: .35rem;
        }
        .right-sub {
            font-size: .9rem;
            color: var(--sub-clr);
            margin-bottom: 1.5rem;
        }

        .error-box {
            background: #FEF2F2;
            border: 1.5px solid #FECACA;
            color: #DC2626;
            border-radius: 10px;
            padding: .75rem 1rem;
            font-size: .85rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .success-box {
            background: #F0FDF4;
            border: 1.5px solid #BBF7D0;
            color: #166534;
            border-radius: 10px;
            padding: .75rem 1rem;
            font-size: .85rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .field { margin-bottom: 1rem; }
        .field label {
            display: block;
            font-size: .8rem;
            font-weight: 700;
            color: var(--label-clr);
            margin-bottom: .4rem;
        }
        .field-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }
        .field-icon {
            position: absolute;
            left: .9rem;
            font-size: .95rem;
            color: var(--icon-clr);
            pointer-events: none;
            display: flex;
            align-items: center;
        }
        .field-wrap input {
            width: 100%;
            padding: .75rem 1rem .75rem 2.5rem;
            border: 1.5px solid var(--input-bd);
            border-radius: 12px;
            font-family: 'Nunito', sans-serif;
            font-size: .95rem;
            color: var(--input-clr);
            background: var(--input-bg);
            outline: none;
            transition: border-color .15s, background .25s, color .25s;
        }
        .field-wrap input:focus {
            border-color: #2563EB;
            background: var(--card-bg);
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }
        .toggle-pass {
            position: absolute;
            right: .9rem;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--icon-clr);
            padding: .2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-submit {
            width: 100%;
            padding: .9rem;
            background: #2563EB;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Nunito', sans-serif;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            transition: background .2s, transform .1s;
            margin-top: .5rem;
        }
        .btn-submit:hover  { background: #1D4ED8; }
        .btn-submit:active { transform: scale(.98); }

        .login-link {
            text-align: center;
            margin-top: 1.25rem;
            font-size: .88rem;
            color: var(--reg-clr);
        }
        .login-link a {
            color: #2563EB;
            font-weight: 800;
            text-decoration: none;
        }
        .login-link a:hover { text-decoration: underline; }

        @media (max-width: 680px) {
            .register-card { grid-template-columns: 1fr; }
            .reg-left { display: none; }
            .reg-right { padding: 2rem 1.5rem; }
        }
    </style>
    <script>
    (function() {
        var t = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', t);
    })();
    </script>
</head>
<body>

<div class="register-card">

    <!-- Panel izquierdo -->
    <div class="reg-left">
        <div class="left-brand">
            <div class="left-brand-icon">
                <img src="{{ asset('img/logo.png') }}" alt="Los Alpes" style="width:32px;height:32px;object-fit:contain;border-radius:6px;">
            </div>
            <div class="left-brand-name">Los Alpes</div>
        </div>

        <div class="left-body">
            <div class="left-title">¡Bienvenido<br>a Los Alpes!</div>
            <div class="left-desc">
                Crea tu cuenta y empieza a disfrutar de nuestros sabores. Haz pedidos, sigue tus entregas y mucho más.
            </div>
        </div>

        <div class="left-footer">
            <div style="display:flex;align-items:center;color:#fff;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 20px; height: 20px;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <div class="left-footer-title">Registro Gratuito</div>
                <div class="left-footer-sub">Sin costo, sin compromisos</div>
            </div>
        </div>
    </div>

    <!-- Panel derecho -->
    <div class="reg-right">
        <div class="right-title">Crea tu cuenta</div>
        <div class="right-sub">Completa los datos para registrarte</div>

        @if (session('error'))
        <div class="error-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; flex-shrink: 0;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif
        @if (session('success'))
        <div class="success-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; flex-shrink: 0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div class="field">
                <label>Nombre completo</label>
                <div class="field-wrap">
                    <span class="field-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    <input type="text" name="nombre"
                           placeholder="Tu nombre completo"
                           value="{{ old('nombre') }}"
                           required autocomplete="name">
                </div>
            </div>

            <div class="field">
                <label>Correo electrónico</label>
                <div class="field-wrap">
                    <span class="field-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </span>
                    <input type="email" name="correo"
                           placeholder="tu@correo.com"
                           value="{{ old('correo') }}"
                           required autocomplete="email">
                </div>
            </div>

            <div class="field">
                <label>Contraseña</label>
                <div class="field-wrap">
                    <span class="field-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input type="password" name="contrasena" id="passInput"
                           placeholder="Mínimo 6 caracteres"
                           required minlength="6" autocomplete="new-password">
                    <button type="button" class="toggle-pass" onclick="togglePass('passInput','eye-open','eye-closed')">
                        <svg id="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg id="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <div class="field">
                <label>Confirmar contraseña</label>
                <div class="field-wrap">
                    <span class="field-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input type="password" name="contrasena2" id="passInput2"
                           placeholder="Repite tu contraseña"
                           required minlength="6">
                    <button type="button" class="toggle-pass" onclick="togglePass('passInput2','eye-open2','eye-closed2')">
                        <svg id="eye-open2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg id="eye-closed2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">Crear cuenta →</button>
        </form>

        <div class="login-link">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a>
        </div>
    </div>

</div>

<script>
function togglePass(inputId, openId, closedId) {
    var input  = document.getElementById(inputId);
    var eyeOpen   = document.getElementById(openId);
    var eyeClosed = document.getElementById(closedId);
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.style.display   = 'none';
        eyeClosed.style.display = 'block';
    } else {
        input.type = 'password';
        eyeOpen.style.display   = 'block';
        eyeClosed.style.display = 'none';
    }
}
</script>

</body>
</html>
