<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StokBahanJadiController extends Controller
{
    public function index()
    {
        return view('billing.stok-bahan-jadi.index');
    }
}
