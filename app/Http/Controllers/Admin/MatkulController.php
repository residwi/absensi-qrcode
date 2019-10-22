<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Matkul;
use App\Http\Controllers\Controller;

class MatkulController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.matkul.index');
    }

    public function getDatatables()
    {
        if (request()->ajax()) {
            $matkul = Matkul::all();

            return datatables()->of($matkul)
                ->addColumn('action', function ($matkul) {
                    return '<form action="' . route('matkul.destroy', $matkul->id) . '" method="POST"> <a href="' . route('matkul.show', $matkul->id) . '" data-toggle="tooltip" data-original-title="Show" title="Show" class="btn btn-info"> <span class="fas fa-fw fa-eye"></span></a> <a href="' . route('matkul.edit', $matkul->id) . '" data-toggle="tooltip" data-original-title="Edit" title="Edit" class="btn btn-success"><span class="fas fa-fw fa-edit"></span></a> ' . csrf_field() . ' ' . method_field("DELETE") . ' <button type="submit" class="btn btn-danger" onclick="return confirm(\'Apakah anda yakin ingin menghapusnya?\')" data-toggle="tooltip" data-original-title="Hapus" title="Hapus"><span class="fas fa-fw fa-trash-alt"></span></button> </form>';
                })
                ->make(true);
        }
    }

    /**
     * Search matkul from ajax
     *
     * @return json
     */
    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->query('q');
            $matkul = Matkul::Where('kode', 'like', '%' . $search . '%')
                ->orWhere('nama', 'like', '%' . $search . '%')
                ->get(['id', 'kode', 'nama']);

            return response()->json($matkul);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.matkul.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' =>  'required|unique:matkul',
            'nama' =>  'required',
            'ruang' => 'required'
        ]);

        Matkul::create([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'ruang' => $validated['ruang']
        ]);

        return redirect()->route('matkul.index')->with('status', 'Mata kuliah berhasil di tambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $matkul = Matkul::findOrFail($id);
        return view('admin.matkul.show', compact('matkul'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $matkul = Matkul::findOrFail($id);
        return view('admin.matkul.edit', compact('matkul'));
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
        $validated = $request->validate([
            'nama' =>  'required',
            'ruang' => 'required'
        ]);

        $matkul = Matkul::find($id);
        $matkul->nama = $validated['nama'];
        $matkul->ruang = $validated['ruang'];
        $matkul->save();

        return redirect()->route('matkul.index')->with('status', 'Mata kuliah berhasil di edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Matkul::find($id)->delete();
        return redirect()->route('matkul.index')->with('status', 'Mata kuliah berhasil di hapus');
    }
}
