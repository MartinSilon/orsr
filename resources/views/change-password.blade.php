<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zoznam konateľov</title>
    <link rel="icon" href="{{ asset('images/logo-shortform.svg') }}" type="image/icon type">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        h5{
            font-weight: 600;
            color: #2d3748 ;
        }

        .navbar{
            background-color: #e2e3e5 !important;
        }
        footer{
            background-color: #2d3748;
        }
        footer p{
            color: #f4f4f4;
        }
        footer a{
            color: #2CC6FF;
            text-decoration: none;
            font-weight: 600;
        }
        .container{
            height: 85vh;
        }
        .card{
            background-color: transparent;
            border: none;
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

        .btn {
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

        .btn:hover {
            background: #008cba;
        }
        .download-btn {
            border-radius: 5px !important;
            background-color: #2d3748;
            border: none;
            text-transform: uppercase;
            font-size: 14px;
            width: 240px;
        }
        .download-btn:hover {
            background-color: #434e60;
        }
    </style>
</head>
<body>
<nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/umbrella-logo.png') }}" alt="Logo"  width="100px" class="ms-4 d-inline-block align-text-top">
        </a>
        <a href="/" class="px-3 py-2 btn btn-primary rounded-0 download-btn me-2">Zoznam konateľov</a>
    </div>
</nav>

<div class="container d-flex justify-content-center align-items-center">
    <div class="card w-50 d-flex justify-content-center align-items-center py-5">
        <h5>Zmena hesla</h5>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

        <form action="{{ route('password.change') }}" method="POST">
            @csrf


            <div class="form-group">
                <label for="new_password">Nové heslo</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Potvrdenie nového hesla</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
            </div>

            <button type="submit" class="btn submit-btn">Zmeniť heslo</button>
        </form>
    </div>
</div>

<footer>
    <div class="container-fluid d-flex justify-content-center py-2">
        <p class="text-center m-0">Copyright © {{ date('Y') }} <a href="https://www.byteminds.sk">Byteminds.sk</a>. Všetky práva vyhradené.</p>
    </div>
</footer>
</body>
</html>

