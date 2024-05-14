<?php

namespace App\Http\Controllers;

use App\Models\db\KategoriProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $data = KategoriProduct::with('products')->get();

        return view('db.product.index', [
            'data' => $data
        ]);
    }
}
