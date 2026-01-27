<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Category::query()->search($request->input('search'))->paginate(10)->withQueryString();
        return Inertia::render('Categories/Index', [
            'categories' => $data,
            'filters' => [
                'search' => $request->input('search'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        Category::create($request->validated());
        return redirect()->route('categories.index')->with('success', 'Kategori Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();
        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Kategori Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori Berhasil Dihapus');
    }

    public function code()
    {
        $lastCode = Category::whereNotNull('code')->orderBy('code', 'desc')->value('code');

        $nextNumber = 1;

        if ($lastCode) {
            $lastNumber = (int)str_replace('CAT-', '', $lastCode);
            $nextNumber = $lastNumber + 1;
        }

        $code = 'CAT-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        return response()->json(
            [
                'message' => 'Code sucessfully',
                'code' => $code,
            ],
            200,
        );
    }
}
