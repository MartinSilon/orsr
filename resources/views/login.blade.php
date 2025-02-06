<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prihlásenie</title>
    <link rel="icon" href="{{ asset('images/logo-shortform.svg') }}" type="image/icon type">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Globálne štýly */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to right, #4facfe, #00f2fe);
        }

        .login-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            font-size: 14px;
            color: #666;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group input:focus {
            border-color: #4facfe;
            outline: none;
            box-shadow: 0 0 5px rgba(79, 172, 254, 0.5);
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: #00A6E3;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-btn:hover {
            background: #008cba;
        }

        .register-link {
            margin-top: 15px;
            font-size: 14px;
            color: #333;
        }


        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>


<div class="login-container">
    <img class="img-fluid mb-4 px-4" src="{{ asset('images/Logo-fullname.svg') }}" alt="">

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" placeholder="Zadajte váš e-mail" required>
        </div>

        <div class="form-group">
            <label for="password">Heslo</label>
            <input type="password" id="password" name="password" placeholder="Zadajte heslo" required>
        </div>

        <button type="submit" class="login-btn">Prihlásiť sa</button>

    </form>
</div>'

{{--<?php--}}
{{--use Illuminate\Support\Facades\Hash;--}}
{{--?>--}}
{{--{{ $hashedPassword = Hash::make('test123') }}--}}

</body>
</html>

