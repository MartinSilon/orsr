<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PDF pre záznam č. {{ $konatel->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #444;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<h1>Detail záznamu č. {{ $konatel->id }}</h1>
<table>
    <tr>
        <th>Meno</th>
        <td>{{ $konatel->meno }}</td>
    </tr>
    <tr>
        <th>Ulica</th>
        <td>{{ $konatel->ulica }}</td>
    </tr>
    <tr>
        <th>PSČ a Mesto</th>
        <td>{{ $konatel->psc_mesto }}</td>
    </tr>
    @if(isset($konatel->firma))
        <tr>
            <th>Firma</th>
            <td>{{ $konatel->firma }}</td>
        </tr>
    @endif
    <tr>
        <th>Vytvorené</th>
        <td>{{ $konatel->created_at }}</td>
    </tr>
</table>
</body>
</html>
