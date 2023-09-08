<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'msg' => 'Berhasil mengakses data',
            'data' => Product::orderBy('name', 'asc')->paginate(5)
        ], 200);
    }

    public function show(Request $request)
    {
        return response()->json([
            'status' => true,
            'msg' => 'Berhasil mendapatkan detail data',
            'data' => Product::find($request->product_id)
        ]);
    }
}
