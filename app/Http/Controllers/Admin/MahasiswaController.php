<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MahasiswaRequest;
use App\Http\Controllers\Controller;
use App\Mahasiswa;
use Illuminate\Http\Request;
use App\User;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.mahasiswa.index');
    }

    public function getDatatables()
    {
        if (request()->ajax()) {
            $mahasiswa = Mahasiswa::getAuthInfo()->get(['id', 'nama', 'alamat', 'jenis_kelamin', 'photo']);

            return datatables()->of($mahasiswa)
                ->editColumn('photo', function ($mahasiswa) {
                    return '<img class="zoom" src="' . asset('vendor/images/users') . '/' . $mahasiswa->photo . '" alt="' . $mahasiswa->nama . '" width="100px">';
                })
                ->addColumn('username', function ($mahasiswa) {
                    return $mahasiswa->authInfo->username;
                })
                ->addColumn('action', function ($mahasiswa) {
                    return '<form action="' . route('mahasiswa.destroy', $mahasiswa->authInfo->username) . '" method="POST"> <a href="' . route('mahasiswa.show', $mahasiswa->authInfo->username) . '" data-toggle="tooltip" data-original-title="Show" title="Show" class="btn btn-info"> <span class="fas fa-fw fa-eye"></span></a> <a href="' . route('mahasiswa.edit', $mahasiswa->authInfo->username) . '" data-toggle="tooltip" data-original-title="Edit" title="Edit" class="btn btn-success"><span class="fas fa-fw fa-edit"></span></a> ' . csrf_field() . ' ' . method_field("DELETE") . ' <button type="submit" class="btn btn-danger" onclick="return confirm(\'Apakah anda yakin ingin menghapusnya?\')" data-toggle="tooltip" data-original-title="Hapus" title="Hapus"><span class="fas fa-fw fa-trash-alt"></span></button> </form>';
                })
                ->removeColumn(['auth_info', 'id'])
                ->rawColumns(['photo', 'action'])
                ->make(true);
        }
    }
    /**
     * Search mahasiswa from ajax
     *
     * @return json
     */
    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->query('q');
            $matkul = Mahasiswa::select('mahasiswa.id', 'users.username', 'mahasiswa.nama')
                ->join('users', 'users.authable_id', '=', 'mahasiswa.id')
                ->where('users.authable_type', 'App\Mahasiswa')
                ->where(function ($query) use ($search) {
                    $query->where('users.username', 'LIKE', '%' . $search . '%')
                        ->orWhere('mahasiswa.nama', 'LIKE', '%' . $search . '%');
                })->get();

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
        return view('admin.mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MahasiswaRequest $request)
    {
        $validated = $request->validated();
        if (isset($validated['photo'])) {
            $imageName = $validated['nomor_induk'] . '-' . time() . '.' . $validated['photo']->extension();
            $validated['photo']->move(public_path('vendor/images'), $imageName);
        }

        $mahasiswa = Mahasiswa::create([
            'nama' => $validated['nama'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'alamat' => $validated['alamat'],
            'email' => $validated['email'],
            'no_telp' => $validated['no_telp'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'photo' => isset($imageName) ?: 'default-user.png',
        ]);

        $mahasiswa->authInfo()->create([
            'username' => $validated['nomor_induk'],
            'password' => \Hash::make($validated['password']),
            'role_id' => 2, //role mahasiswa
        ]);

        return redirect()->route('mahasiswa.index')->with('status', 'Mahasiswa berhasil di tambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mahasiswa = User::with('authable')->where('username', $id)->firstOrFail();
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $mahasiswa = User::with('authable')->where('username', $id)->firstOrFail();
        return view('admin.mahasiswa.edit', compact('mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MahasiswaRequest $request, $id)
    {
        $mahasiswa = User::with('authable')->where('username', $id)->firstOrFail();
        $validated = $request->validated();

        $mahasiswa->authable->nama = $validated['nama'];
        $mahasiswa->authable->tanggal_lahir = $validated['tanggal_lahir'];
        $mahasiswa->authable->alamat = $validated['alamat'];
        $mahasiswa->authable->email = $validated['email'];
        $mahasiswa->authable->no_telp = $validated['no_telp'];
        $mahasiswa->authable->jenis_kelamin = $validated['jenis_kelamin'];
        if (isset($validated['photo'])) {
            if ($mahasiswa->authable->photo != 'default-user.png') {
                unlink(public_path('vendor/images/' . $mahasiswa->authable->photo));
            }
            $imageName = $validated['nomor_induk'] . '-' . time() . '.' . $validated['photo']->extension();
            $mahasiswa->authable->photo = $imageName;
            $validated['photo']->move(public_path('vendor/images'), $imageName);
        }

        $mahasiswa->authable->save();

        if (isset($validated['password'])) {
            $mahasiswa->password = \Hash::make($validated['password']);
            $mahasiswa->save();
        }

        return redirect()->route('mahasiswa.index')->with('status', 'Mahasiswa berhasil di edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::where('username', $id)->first();
        $mahasiswa = Mahasiswa::find($user->authable_id);
        $mahasiswa->delete();
        $user->delete();

        return redirect()->route('mahasiswa.index')->with('status', 'Mahasiswa berhasil di hapus');
    }
}
