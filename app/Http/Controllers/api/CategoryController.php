<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DefaultCategory;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Vcard;

class CategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user && $user->user_type === 'V') {
            $vcard = Vcard::where('phone_number', $user->id)->firstOrFail();
            return CategoryResource::collection($vcard->categories);
        }
        return CategoryResource::collection(DefaultCategory::all());
    }

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
        $storedCategory->custom_data = json_encode(["icon" => $dataToSave['icon']]);


        $storedCategory->save();
        return new CategoryResource($storedCategory);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $dataToSave = $request->validated();
        $updatedCategory = Category::find($category->id);

        $updatedCategory->name = $dataToSave['name'];
        $updatedCategory->type = $dataToSave['type'];
        $updatedCategory->custom_data = json_encode(["icon" => $dataToSave['icon']]);
        $updatedCategory->save();
        return new CategoryResource($updatedCategory);
    }

    public function updateDefault(UpdateCategoryRequest $request, DefaultCategory $category)
    {
        $dataToSave = $request->validated();
        
        $updatedCategory = DefaultCategory::find($category->id);

        $updatedCategory->name = $dataToSave['name'];
        $updatedCategory->type = $dataToSave['type'];
        $updatedCategory->custom_data = json_encode(["icon" => $dataToSave['icon']]);
        $updatedCategory->save();
        return new CategoryResource($updatedCategory);
    }

    public function destroy(Category $category)
    {
        $deletedCategory = Category::find($category->id);
        if (!$deletedCategory) {
            return response()->json(['error' => 'Category not found'], 404);
        }
        $deletedCategory->delete();

        return new CategoryResource($deletedCategory);
    }

    public function destroyDefault(DefaultCategory $category)
    {
        $deletedCategory = DefaultCategory::find($category->id);
        if (!$deletedCategory) {
            return response()->json(['error' => 'Category not found'], 404);
        }
        $deletedCategory->delete();

        return new CategoryResource($deletedCategory);
    }
}