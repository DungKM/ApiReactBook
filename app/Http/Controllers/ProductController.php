<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $category;
    protected $brand;
    protected $product;
    public function __construct(Category $category, Brand $brand, Product $product)

    {
        $this->category = $category;
        $this->product = $product;
        $this->brand = $brand;
    }
    public function index()
    {
        $products = $this->product->with('category')->with('brand')->where('quantity', '>', 0)
            ->latest('id')
            ->paginate(7);
        return response()->json($products);
    }
    public function create()
    {
        $category = $this->category->get();
        $brand = $this->brand->get();

        return response()->json([
            'categories' => $category,
            'brands' => $brand,
        ]);
    }
    public function store(StoreProductRequest $request)
    {
        try {
            $data = $request->validated();
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $name);

            $data['image'] = $name;

            $product = $this->product->create($data);

            return response()->json([
                $product,
                'message' => 'Create successed'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went really wrong!"
            ], 500);
        }
    }
    public function show(Product $product)
    {
        return response()->json($product);
    }
    public function edit(Product $product)
    {
        return response()->json($product);
    }
    public function update(StoreProductRequest $request, Product $product)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                Storage::delete('public/images/' . $product->image);
                $data['image'] = $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('public/images', $data['image']);
            } else {
                $data['image'] = $product->image;
            }
            $product->update($data);
            return response()->json([
                $product,
                "message" => "Update Success"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went really wrong!"
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        Storage::delete('public/images/' . $product->image);
        $product->delete();
        return response()->json([
            $product,
            "message" => "Delete successed"
        ]);
    }
}