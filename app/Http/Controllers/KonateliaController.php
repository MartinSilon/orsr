<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Konatelia;
use Illuminate\Support\Facades\DB;

class KonateliaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'konatelia' => 'required|array',
            'konatelia.*.meno' => 'required|string|unique:konatelia',
            'konatelia.*.ulica' => 'required|string',
            'konatelia.*.psc_mesto' => 'required|string',
        ]);

        foreach ($request->konatelia as $konatel) {
            Konatelia::create($konatel);
        }

        $uniqueIds = DB::table('konatelia')
            ->select(DB::raw('MIN(id) as id'))
            ->groupBy('meno', 'ulica', 'psc_mesto', 'created_at')
            ->pluck('id')
            ->toArray();

        DB::table('konatelia')
            ->whereNotIn('id', $uniqueIds)
            ->delete();
    }
}

