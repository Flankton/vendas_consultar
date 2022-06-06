<?php

namespace App\Http\Controllers;

class VendasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //

    public function vendas(){

        return env('API_URL');
    }
}
