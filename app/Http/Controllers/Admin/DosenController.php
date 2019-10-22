<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DosenRequest;
use App\Dosen;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dosen.index');
    }

    public function getDatatables()
    {
        if (request()->ajax()) {
            $dosen = Dosen::getAuthInfo()->get(['id', 'nama', 'alamat', 'jenis_kelamin', 'photo']);

            return datatables()->of($dosen)
                ->editColumn('photo', function ($dosen) {
                    return '<img class="zoom" src="' . asset('vendor/images/users') . '/' . $dosen->photo . '" alt="' . $dosen->nama . '" width="100px">';
                })
                ->addColumn('username', function ($dosen) {
                    if (isset($dosen->authInfo->username)) {
                        return $dosen->authInfo->username;
                    }
                })
                ->addColumn('action', function ($dosen) {
                    if (isset($dosen->authInfo->username)) {
                        return '<form action="' . route('dosen.destroy', $dosen->authInfo->username) . '" method="POST"> <a href="' . route('dosen.show', $dosen->authInfo->username) . '" data-toggle="tooltip" data-original-title="Show" title="Show" class="btn btn-info"> <span class="fas fa-fw fa-eye"></span></a> <a href="' . route('dosen.edit', $dosen->authInfo->username) . '" data-toggle="tooltip" data-original-title="Edit" title="Edit" class="btn btn-success"><span class="fas fa-fw fa-edit"></span></a> ' . csrf_field() . ' ' . method_field("DELETE") . ' <button type="submit" class="btn btn-danger" onclick="return confirm(\'Apakah anda yakin ingin menghapusnya?\')" data-toggle="tooltip" data-original-title="Hapus" title="Hapus"><span class="fas fa-fw fa-trash-alt"></span></button> </form>';
                    }
                })
                ->removeColumn(['auth_info', 'id'])
                ->rawColumns(['photo', 'action'])
                ->make(true);
        }
    }

    /**
     * search dosen from ajax
     *
     * @return json
     */
    public function ajaxSearch(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->query('q');
            $matkul = Dosen::select('dosen.id', 'users.username', 'dosen.nama')
                ->join('users', 'users.authable_id', '=', 'dosen.id')
                ->where('users.authable_type', 'App\Dosen')
                ->where(function ($query) use ($search) {
                    $query->where('users.username', 'LIKE', '%' . $search . '%')
                        ->orWhere('dosen.nama', 'LIKE', '%' . $search . '%');
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
        return view('admin.dosen.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DosenRequest $request)
    {
        $validated = $request->validated();
        if (isset($validated['photo'])) {
            $imageName = $validated['nomor_induk'] . '-' . time() . '.' . $validated['photo']->extension();
            $validated['photo']->move(public_path('vendor/images'), $imageName);
        }

        $dosen = Dosen::create([
            'nama' => $validated['nama'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'alamat' => $validated['alamat'],
            'email' => $validated['email'],
            'no_telp' => $validated['no_telp'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'photo' => isset($imageName) ?: 'default-user.png',
        ]);

        $dosen->authInfo()->create([
            'username' => $validated['nomor_induk'],
            'password' => \Hash::make($validated['password']),
            'role_id' => 2, //role dosen
        ]);

        return redirect()->route('dosen.index')->with('status', 'Dosen berhasil di tambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dosen = User::with('authable')->where('username', $id)->firstOrFail();
        return view('admin.dosen.show', compact('dosen'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dosen = User::with('authable')->where('username', $id)->firstOrFail();
        return view('admin.dosen.edit', compact('dosen'));
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
        $dosen = User::with('authable')->where('username', $id)->first();

        $validated = $request->validate([
            'nomor_induk' => 'required|unique:users,username,' . $dosen->id,
            'nama' => 'required',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required',
            'email' => 'required|email|unique:dosen,email,' . $dosen->authable->id,
            'password' => 'nullable|confirmed',
            'photo' => 'nullable|image|max:2048',
        ]);
        $dosen->authable->nama = $validated['nama'];
        $dosen->authable->tanggal_lahir = $validated['tanggal_lahir'];
        $dosen->authable->alamat = $validated['alamat'];
        $dosen->authable->email = $validated['email'];
        $dosen->authable->no_telp = $validated['no_telp'];
        $dosen->authable->jenis_kelamin = $validated['jenis_kelamin'];
        if (isset($validated['photo'])) {
            if ($dosen->authable->photo != 'default-user.png') {
                unlink(public_path('vendor/images/' . $dosen->authable->photo));
            }
            $imageName = $validated['nomor_induk'] . '-' . time() . '.' . $validated['photo']->extension();
            $dosen->authable->photo = $imageName;
            $validated['photo']->move(public_path('vendor/images'), $imageName);
        }

        $dosen->authable->save();

        if (isset($validated['password'])) {
            $dosen->password = \Hash::make($validated['password']);
            $dosen->save();
        }

        return redirect()->route('dosen.index')->with('status', 'Dosen berhasil di edit');
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
        $dosen = Dosen::find($user->authable_id);
        $dosen->delete();
        $user->delete();

        return redirect()->route('dosen.index')->with('status', 'Dosen berhasil di hapus');
    }
}
