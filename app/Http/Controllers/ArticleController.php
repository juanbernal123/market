<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        return Article::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'description' => 'required',
        ]);

        $article              = new Article;
        $article->name        = $request->name;
        $article->description = $request->description;
        $article->save();

        return $article;
    }

    public function show(Article $article)
    {
        return $article;
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'name'        => 'required',
            'description' => 'required',
        ]);

        $article->name        = $request->name;
        $article->description = $request->description;
        $article->save();

        return $article;
    }

    public function destroy(Article $article)
    {
        $article->delete();

        return response()->noContent(); // 204
    }
}
