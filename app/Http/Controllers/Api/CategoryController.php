<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidateCategory;
use App\Http\Resources\CategoriesCollection;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->sendResponse(new CategoriesCollection(Category::paginate(4)), 'Categories retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ValidateCategory $request)
    {
        $created = Category::create($request->only(['name']));
        return $this->sendResponse($created, 'Category Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        return $this->sendResponse($category, 'Category retrieved Successfully');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ValidateCategory $request, Category $category)
    {
        if($category->update($request->only(['name']))){
            return $this->sendResponse($category, 'Category Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Category $category)
    {
        $deleted = $category;
        if($category->delete()){
            return $this->sendResponse($deleted, 'Category Deleted Successfully');
        }
    }
}
