<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
  /**
     * Display a listing of the resource.
     */
    protected $category;
    protected $brand;
    protected $product;
    public function __construct(Category $category,Brand $brand, Product $product)
    
    {
        $this->category = $category;
        $this->product = $product;
        $this->brand = $brand;
    }
    public function index()
    {
        $products = $this->product->with('category')->with('brand')->latest('id')->paginate(5);
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    
    public function create()
    {
        $category = $this->category->get();
        $brand = $this->brand->get();
        
        return response()->json([
           'categories' => $category,
           'brands' => $brand,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return response()->json($product);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                Storage::delete('public/images/' . $product->image);
                $data['image'] = $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('public/images', $data['image']);
            }else {
                // Nếu không có ảnh mới, sử dụng lại giá trị hiện tại của trường image trong cơ sở dữ liệu
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


    /**
     * Remove the specified resource from storage.
     */
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