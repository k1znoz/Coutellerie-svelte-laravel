<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Knife;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class KnifeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $knives = Cache::remember('knives.all', 3600, function() {
            return Knife::all();
        });

        // Mapper les champs pour correspondre à l'interface frontend
        $mappedKnives = $knives->map(function ($knife) {
            $images = $knife->images ?: [];
            
          
            $fullPathImages = collect($images)->map(function ($image) {
                return url(Storage::url($image));
            })->toArray();

            return [
                'id' => $knife->id,
                'title' => $knife->name,
                'category' => $knife->category,
                'images' => $fullPathImages,
                'description' => $knife->description,
                'type' => $knife->type,
                'length' => $knife->length,
                'material' => $knife->material,
                'price' => $knife->price,
            ];
        });

        return response()->json([
            'data' => $mappedKnives,
            'count' => $knives->count()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'images' => 'required|array',
            'images.*' => 'string',
            'type' => 'required|string|max:255',
            'length' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $knife = Knife::create($validated);

        return response()->json(['data' => $knife], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $knife = Knife::find($id);

        if (!$knife) {
            return response()->json(['error' => 'Couteau non trouvé'], 404);
        }

       
        $images = $knife->images ?: [];
        $fullPathImages = collect($images)->map(function ($image) {
            return url(Storage::url($image)); 
        })->toArray();

        $mappedKnife = [
            'id' => $knife->id,
            'title' => $knife->name,
            'category' => $knife->category,
            'images' => $fullPathImages,
            'description' => $knife->description,
            'type' => $knife->type,
            'length' => $knife->length,
            'material' => $knife->material,
            'price' => $knife->price,
        ];

        return response()->json(['data' => $mappedKnife]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $knife = Knife::find($id);

        if (!$knife) {
            return response()->json(['error' => 'Couteau non trouvé'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'images' => 'sometimes|array',
            'images.*' => 'string',
            'type' => 'sometimes|string|max:255',
            'length' => 'sometimes|string|max:255',
            'material' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
        ]);

        $knife->update($validated);

        return response()->json(['data' => $knife]);
    }

    /**
     * Get all available categories
     */
    public function categories()
    {
        $categories = Knife::getAvailableCategories();
        
        return response()->json([
            'data' => $categories,
            'count' => count($categories)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $knife = Knife::find($id);

        if (!$knife) {
            return response()->json(['error' => 'Couteau non trouvé'], 404);
        }

        $knife->delete();

        return response()->json(['message' => 'Couteau supprimé avec succès']);
    }
}
