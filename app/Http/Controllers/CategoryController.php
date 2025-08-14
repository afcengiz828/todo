<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResources;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json(["data" => CategoryResources::collection($categories)]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string',
        ]);
        
        $category = Category::create($validatedData);
        //dd($category);
        return response()->json([
            'message' => 'Kategori başarıyla oluşturuldu.',
            'category' => new CategoryResources($category),
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Kategori bulunamadı.'
            ], Response::HTTP_NOT_FOUND); // 404 Not Found
        }

        return response()->json(new CategoryResources($category));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Kategori bulunamadı.'
            ], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string',
        ]);
        
        $category->update($validatedData);

        return response()->json([
            'message' => 'Kategori başarıyla güncellendi.',
            'category' => new CategoryResources($category),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Kategori bulunamadı.'
            ], Response::HTTP_NOT_FOUND);
        }

        $category->delete();

        return response()->json([
            'message' => 'Kategori başarıyla silindi.',
            "data" => new CategoryResources($category),
        ]); 
    }

    public function todos($id)  {
        
        $category = Category::with("todos")->find( $id );

        if (!$category) {
            return response()->json([
                'message' => 'Kategori bulunamadı.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json(["data" => CategoryResources::collection($category)]);
    }
}
