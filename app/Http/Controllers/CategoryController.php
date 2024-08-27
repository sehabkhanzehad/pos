<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{


    public function showCategoryPage(){
        return view('pages.dashboard.category-page');
    }


    public function store(Request $request)
    {
        try {
            $userId = $request->header('id');
            $categoryName = $request->input("name");

            Category::create([
                "user_id" => $userId,
                "name" => $categoryName,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Category created successfully.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => "Something went wrong, Please try again.",
            ], 500);
        }
    }

    public function categoryList(Request $request)
    {
        $userId = $request->header('id');
        $userEmail = $request->header('email');

        return Category::where('user_id', $userId)->get();
    }

    public function updateCategory(Request $request, Category $categoryId){

        $userId = $request->header('id');
        $categoryName = $request->input("name");


    }
}
