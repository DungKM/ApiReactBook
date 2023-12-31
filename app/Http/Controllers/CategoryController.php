<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    protected $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }
    public function index()
    {
        $categories = $this->category->latest('id')->paginate(5);
        return response()->json($categories);
    }
    public function store(StoreCategoryRequest $request)
    {
        try {
            $data = $request->validated();
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $name);

            $data['image'] = $name;

            $category = $this->category->create($data);

            return response()->json([
                $category,
                'message' => 'Create successed'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went really wrong!"
            ], 500);
        }
    }
    public function show(Category $category)
    {
        return response()->json($category);
    }
    public function edit(Category $category)
    {
        return response()->json($category);
    }
    public function update(StoreCategoryRequest $request, Category $category)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                Storage::delete('public/images/' . $category->image);
                $data['image'] = $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('public/images', $data['image']);
            } else {
                // Nếu không có ảnh mới, sử dụng lại giá trị hiện tại của trường image trong cơ sở dữ liệu
                $data['image'] = $category->image;
            }
            $category->update($data);
            return response()->json([
                $category,
                "message" => "Update Success"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went really wrong!"
            ], 500);
        }
    }
    public function destroy(Category $category)
    {
        Storage::delete('public/images/' . $category->image);
        $category->delete();
        return response()->json([
            $category,
            "message" => "Delete successed"
        ]);
    }
}
