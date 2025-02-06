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

        .custom-table {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .download-btn {
            border-radius: 5px !important;
            background-color: #2d3748;
            border: none;
            text-transform: uppercase;
            font-size: 14px;

        }
        .download-btn:hover {
            background-color: #434e60;
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

        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            padding: 10px 0;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination li a,
        .pagination li span {
            display: block;
            padding: 8px 12px;
            background: #2d3748;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
            border: none;
        }


        .pagination .active span {
            background: #2CC6FF;
            font-weight: bold;
        }

        .pagination .disabled span {
            background: #ccc;
            color: #666;
            cursor: not-allowed;
        }

    </style>
</head>
<body>
<nav class="navbar bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/umbrella-logo.png') }}" alt="Logo"  width="100px" class="ms-4 d-inline-block align-text-top">
        </a>
        @if($konatelia->count() > 0)
        <a href="/generate-company-pdfs" class="px-3 py-2 btn btn-primary rounded-0 download-btn me-2">Stiahnúť nové záznamy</a>
        @endif



    </div>
</nav>

<div class="container py-5">
    @if(session('error'))
        <div class="alert alert-success">
            {{ session('error') }}
        </div>
    @endif


    @if($konatelia->count() > 0)
        <h5 class=" mb-4 text-uppercase">Nové <span>záznamy</span></h5>
        <div class="table-responsive mb-5">
            <table class="table table-striped table-hover custom-table">
                <thead class="table-secondary">
                <tr>
                    <th>Meno</th>
                    <th>Adresa</th>
                    <th>Mesto a PSČ</th>
                </tr>
                </thead>
                <tbody>
                @foreach($konatelia as $item)
                    <tr>
                        <td>{{ $item->meno }}</td>
                        <td>{{ $item->ulica }}</td>
                        <td>{{ $item->psc_mesto }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

    @endif

    <h5 class=" mb-4 text-uppercase">Staré stiahnuté <span>záznamy</span></h5>
    <div class="table-responsive d-flex justify-content-center">
        <table class="table table-striped table-hover custom-table">
            <thead class="table-secondary">
            <tr>
                <th>Meno</th>
                <th>Adresa</th>
                <th>Mesto a PSČ</th>
                <th>Vytvorené</th>
            </tr>
            </thead>
            <tbody>
            @foreach($konateliaTrashed as $item)
                <tr>
                    <td>{{ $item->meno }}</td>
                    <td>{{ $item->ulica }}</td>
                    <td>{{ $item->psc_mesto }}</td>
                    <td>{{ $item->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $konateliaTrashed->links() }}
</div>

<footer>
    <div class="container-fluid d-flex justify-content-between py-2">
        <p class="text-center m-0">Copyright © {{ date('Y') }} <a href="https://www.byteminds.sk">Byteminds.sk</a>. Všetky práva vyhradené.</p>

        <div>
            <a href="{{ route('password.change.form') }}">Zmeniť heslo</a>
{{--            <form action="{{ route('logout') }}" method="POST" style="display: inline;">--}}
{{--                @csrf--}}
{{--                <button type="submit">Logout</button>--}}
{{--            </form>--}}
        </div>

    </div>
</footer>
</body>
</html>
