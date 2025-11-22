<?php

namespace App\Http\Controllers;

use App\Models\Knife;
use Illuminate\Http\Request;

class KnifeController extends Controller
{
    public function index()
    {
        $knives = Knife::with(['category', 'types', 'materials'])
            ->where('available', true)
            ->get();

        return response()->json([
            'data' => $knives,
            'count' => $knives->count()
        ]);
    }

    public function show($id)
    {
        $knife = Knife::with(['category', 'types', 'materials'])
            ->findOrFail($id);

        return response()->json([
            'data' => $knife
        ]);
    }
}