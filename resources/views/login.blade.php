<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">
</head>
<body>

    <div class="login-form">
        <h2>Login Page</h2>

        <form action="login" method = "post">
            @csrf
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Login</button>
            <p>Don't have an account? <a href="{{ route('register') }}">Register here</a>.</p>
        </form>
    </div>

    <script>
        @if($message == "User registered successfully")
            alert("{{ $message }}");
        @endif

        @if($message == "User already exists")
            alert("{{ $message }}");
        @endif

        @if($message == "Login failed")
            alert("{{ $message }}");
        @endif
    </script>

</body>
</html>
