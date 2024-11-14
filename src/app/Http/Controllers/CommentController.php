<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // コメント投稿
    public function store(Request $request, $item_id)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);
        $product = Product::findOrFail($item_id);
        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->product_id = $product->id;
        $comment->content = $request->content;
        $comment->save();
        return back()->with('success', 'コメントを投稿しました');
    }
}
