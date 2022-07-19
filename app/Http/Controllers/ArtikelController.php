<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $artikel = DB::table('artikels')->join('kategoris', 'artikels.kategori_id', '=', 'kategoris.id_kategori')->
                    join('users', 'artikels.user_id', '=', 'users.id_user')->select('artikels.*', 'kategoris.nama_kategori', 'users.name')->get();
        return response()->json(['data' => $artikel], 200);
    }

    public function searchByCategory(Request $request)
    {    
        $artikel = DB::table('artikels')->join('kategoris', 'artikels.kategori_id', '=', 'kategoris.id_kategori')->
        join('users', 'artikels.user_id', '=', 'users.id_user')->select('artikels.*', 'kategoris.nama_kategori', 'users.name');
        if ($request->bycategory) {
            $artikels = $artikel->where('kategori_id', 'LIKE', "%".$request->bycategory."%")->get();
            return response()->json(['data' => $artikels], 200);
        }
    }

    public function searchByName(Request $request)
    {
        $artikel = DB::table('artikels')->join('kategoris', 'artikels.kategori_id', '=', 'kategoris.id_kategori')->
        join('users', 'artikels.user_id', '=', 'users.id_user')->select('artikels.*', 'kategoris.nama_kategori', 'users.name');
        if ($request->byname) {
            $artikels = $artikel->where('judul', 'LIKE', "%".$request->byname."%")->get();
            return response()->json(['data' => $artikels], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $foto = [];
        if ($request->foto) {
            $foto = $request->foto->getClientOriginalName();
            $request->foto->move(public_path('img'), $foto);
        }

        Artikel::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'foto' => $foto,
            'kategori_id' => $request->kategori_id,
            'user_id' => 7
        ]);

        return response()->json(['message' => 'artikel created'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $artikel = Artikel::find($id);
        return response()->json(['data' => $artikel], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $foto = [];
        if ($request->foto) {
            $foto = $request->foto->getClientOriginalName();
            $request->foto->move(public_path('img'), $foto);
        }
        Artikel::find($id)->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'foto' => $foto,
            'kategori_id' => $request->kategori_id,
            'user_id' => 7
        ]);

        return response()->json(['message' => 'data updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Artikel::find($id)->delete();
        return response()->json(['message' => 'data deleted'], 200);
    }
}
