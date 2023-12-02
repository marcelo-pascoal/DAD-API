<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DefaultCategory;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->user_type === 'V') {
            return CategoryResource::collection($user->categories);
        }
        return CategoryResource::collection(DefaultCategory::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdateCategoryRequest $request)
    {
        $dataToSave = $request->validated();

        $user = Auth::user();
        if ($user && $user->user_type === 'V') {
            $storedCategory = new Category();
            $storedCategory->vcard = $user->id;
        } else {
            $storedCategory = new DefaultCategory();
        }

        $storedCategory->name = $dataToSave['name'];
        $storedCategory->type = $dataToSave['type'];

        $storedCategory->save();
        return new CategoryResource($storedCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, DefaultCategory $category)
    {
        $dataToSave = $request->validated();
        $user = Auth::user();
        if ($user && $user->user_type === 'V') {
            $updatedCategory = Category::find($category->id);
        } else {
            $updatedCategory = DefaultCategory::find($category->id);
        }
        $updatedCategory->fill($dataToSave);
        $updatedCategory->save();
        return new CategoryResource($updatedCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DefaultCategory $category)
    {
        $user = Auth::user();
        if ($user && $user->user_type === 'V') {
            $deletedCategory = Category::find($category->id);
        } else {
            $deletedCategory = DefaultCategory::find($category->id);
        }

        if (!$deletedCategory) {
            return response()->json(['error' => 'Category not found'], 404);
        }
        $deletedCategory->delete();

        return new CategoryResource($deletedCategory);
    }
}
