<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($product_id)
    {
        // Lấy danh sách tất cả các bình luận dựa trên product_id
        $comments = Comment::where('product_id', $product_id)->with(['product','user'])->get();

        // Trả về danh sách bình luận dưới dạng API JSON
        return response()->json(['comments' => $comments]);
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
    public function store(Request $request)
    {
        // Lấy dữ liệu từ yêu cầu (request)
        $data = $request->all();

        // Tạo mới bình luận
        $comment = Comment::create($data);

        // Trả về thông báo thành công và bình luận vừa tạo
        return response()->json(['message' => 'Bình luận đã được tạo thành công', 'comment' => $comment]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        //
    }
}