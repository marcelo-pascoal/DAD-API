<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\DefaultCategory;
use App\Http\Resources\DefaultCategoryResource;
use App\Http\Resources\CategoryResource;

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

        $categories = DefaultCategory::all();
        return DefaultCategoryResource::collection($categories);
    }

    public function getCategoriesOfUser(User $user)
    {
        return CategoryResource::collection($user->categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
