<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\DetailsResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        // Untuk Menampilkan List tabel article
        $article=Article::all();

        return response()->json(
            [
                'status' => 200,
                'message' => null,
                'data' => ArticleResource::collection($article)
            ]
        );
    }

    public function ArticleDetail($id)
    {
        $article=Article::findOrFail($id);
        
        return response()->json(
            [
                'status' => 200,
                'message' => null,
                'data' => new DetailsResource($article)
            ]
        );
    }

    // Untuk Menambah Artikel
    public function ArticleUpload(Request $req)
    {
        // Untuk Menambah Artikel dan Gambar
        if($req -> hasFile('image'))
        {
            $article = new article;
            $article -> title = $req->title;
            $article -> content = $req->content;
            $image = $req->file('image');

            $slug_title = Str::slug($req->title,'-');
            $namaGambar = $slug_title.'-'.rand().'.'.$image->getClientOriginalExtension();
            $destinationPath = storage_path('app\gambar-artikel');
            $UrlDanNamaGambar = $image->move($destinationPath, $namaGambar);
            $article -> image = $UrlDanNamaGambar;

            $article -> save();

            return response()->json(
                [
                        'status' => 201,
                        'message' => "Article has been successfully posted",
                        'data' => new ArticleResource($article)
                ]
            );

        }
        
        // Untuk Menambah Artikel Saja
        else{
            $sample = "null";

            $article = new article;
            $article -> title = $req->title;
            $article -> content = $req->content;
            $article -> image = $sample;
            $article -> save();

            return response()->json(
                [
                         'status' => 201,
                         'message' => "Article has been successfully posted",
                         'data' => new ArticleResource($article)
                 ]
             );
        }

    }

    // Untuk Menghapus Artikel
    public function ArticleDelete(Request $id)
    {    
        $article = article::findOrFail($id->id);
        $image_path = $article->image;

        if(File::exists($image_path)) {
            File::delete($image_path);
            $article->delete();

            $article->update(['article' => null]);
            return response()->json(
                [
                    'status' => 200,
                    'message' => "Article has been successfully deleted",
                    'data' => null
                ]
             );
        }
        else{
            $article->delete();
            $article->update(['article' => null]);
            return response()->json(
                [
                    'status' => 200,
                    'message' => "Article has been successfully deleted",
                    'data' => null
                ]
             );
        }
    
    }

}
