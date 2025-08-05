<?php

namespace App\Http\Controllers;

use App\Models\ArtikelModel;
use Illuminate\Http\Request;

class artikelController extends Controller
{
    // get all artikel data 
    function getAllArticleData()
    {

        $data = ArtikelModel::get();

        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    // addnew article
    public function addArticle(Request $request)
    {
        ArtikelModel::create([
            'judul' => $request->input('title'),
            'konten' => $request->input('content'),
            'penulis' => $request->input('writer'),
            'kategori' => $request->input('category'),
            'tanggal_publish' => $request->input('publish_date'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Article saved successfully!',
        ]);
    }

    // get article by id
    public function getArticle($id)
    {
        $data = ArtikelModel::findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    // update article
    public function updateArticle(Request $request, $id)
    {
        $artikel = ArtikelModel::findOrFail($id);

        $artikel->update([
            'judul' => $request->input('title'),
            'konten' => $request->input('content'),
            'penulis' => $request->input('writer'),
            'kategori' => $request->input('category'),
            'tanggal_publish' => $request->input('publish_date'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Article updated'
        ]);
    }

    // delete user
    public function deleteArticle($id)
    {
        $artikel = ArtikelModel::findOrFail($id);

        $artikel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully'
        ], 200);
    }
}
