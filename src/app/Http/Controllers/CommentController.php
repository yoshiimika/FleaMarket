<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $comment = new Comment();
        $comment->user_id = auth()->id();
        $comment->item_id = $item->id;
        $comment->content = $request->content;
        $comment->save();

        return redirect()->route('product.show', $item_id)
            ->with('success', 'コメントを投稿しました！');
    }
}
