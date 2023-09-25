<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $brand;
    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }
    public function index()
    {
        $brands = $this->brand->latest('id')->paginate(5);
        return response()->json($brands);
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
    public function store(StoreBrandRequest $request)
    {
        try {
            $data = $request->validated();
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $name);

            $data['image'] = $name;

            $brand = $this->brand->create($data);

            return response()->json([
                $brand,
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
    public function show(Brand $brand)
    {
        return response()->json($brand);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return response()->json($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBrandRequest $request, Brand $brand)
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('image')) {
                Storage::delete('public/images/' . $brand->image);
                $data['image'] = $request->file('image')->getClientOriginalName();
                $request->file('image')->storeAs('public/images', $data['image']);
            }else {
                // Nếu không có ảnh mới, sử dụng lại giá trị hiện tại của trường image trong cơ sở dữ liệu
                $data['image'] = $brand->image;
            }
            $brand->update($data);
            return response()->json([
                $brand,
                'message' => 'Update successed'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Something went really wrong!"
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        Storage::delete('public/images/' . $brand->image);
        $brand->delete();
        return response()->json([
            $brand,
            "messgae" => "Delete successed"
        ]);
    }
}