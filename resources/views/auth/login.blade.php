<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <form action="{{ route('login.post') }}" method="POST">
        @csrf  {{-- ← IMPORTANTE: protección contra ataques --}}

        @if ($errors->any())
            <div>{{ $errors->first() }}</div>
        @endif

        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <label>
            <input type="checkbox" name="remember"> Recordarme
        </label>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
