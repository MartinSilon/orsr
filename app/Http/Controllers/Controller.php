<?php

namespace App\Http\Controllers;

use App\Models\Konatelia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function show()
    {
        $konateliaTrashed = Konatelia::onlyTrashed()->paginate(20);
        $konatelia = Konatelia::all();

        return view('show', compact('konatelia', 'konateliaTrashed'));
    }

}
