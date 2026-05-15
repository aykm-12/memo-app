<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        // 削除実行
        $category->delete();

        // 204 No Content (成功したが返すデータはない) を返却
        return response()->noContent();
    }
}
