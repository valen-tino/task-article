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
        $article = new article;
        $article -> title = $req->title;
        $article -> content = $req->content;

        if($req -> hasFile('image'))
        {
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

    public function ArticleEdit(Request $req, $id)
    {
        // Untuk Mengubah Artikel dan Gambar

            $article = article::findOrFail($id);

            if ($req->hasFile('image')){

                $image_path = storage_path('app\gambar-artikel');
                if (File::exists($image_path)) {
                    File::delete($image_path);
                }

                $image = $req->file('image');
                $slug_title = Str::slug($req->title,'-');
                $namaGambar = $slug_title.'-'.rand().'.'.$image->getClientOriginalExtension();
                $UrlDanNamaGambar = $image->move($image_path, $namaGambar);

                $article -> image = $UrlDanNamaGambar;
                $article -> title = $req->input('title');
                $article -> content = $req->input('content');
                $article -> save();
                
                return response()->json(
                    [
                        'status' => 200,
                        'message' => "Article has been successfully Updated",
                        'data' => new ArticleResource($article)
                    ]
                 );
            } 
              
            else {
                $article -> title = $req->input('title');
                $article -> content = $req->input('content');
                
            }

    }

    // Untuk Menghapus Artikel
    public function ArticleDelete(Request $id)
    {    
        $article = article::findOrFail($id->id);
        $image_path = $article->image;

        // Untuk Menghapus Artikel yang ada gambarnya
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

        // Untuk Menghapus Artikel yang tidak ada gambarnya
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
