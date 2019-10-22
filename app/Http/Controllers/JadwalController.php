<?php

namespace App\Http\Controllers;

use App\Dosen;
use App\Jadwal;
use App\Jurnal;
use App\Mahasiswa;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.jadwal.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.jadwal.create');
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
            'matkul' =>  'required|unique:jadwal,matkul_id',
            'dosen' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $dosen = Dosen::find($validated['dosen']);
        $jadwal = $dosen->schedules()->create([
            'matkul_id' => $validated['matkul'],
            'hari' => $validated['hari'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
        ]);

        // membuat 14x pertemuan di jurnal
        $jurnal = [];
        for ($i = 1; $i <= 14; $i++) {
            array_push($jurnal, new Jurnal(['pertemuan' => $i]));
        }

        $jadwal->jurnals()->saveMany($jurnal);

        return redirect()->route('jadwal.index')->with('status', 'Jadwal berhasil di tambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jadwal = Jadwal::with(['matkul:id,kode,nama', 'schedulable.authInfo'])->whereId($id)->firstOrFail();

        return view('admin.jadwal.show', compact('jadwal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jadwal = Jadwal::with(['matkul:id,kode,nama', 'schedulable.authInfo'])->whereId($id)->firstOrFail();

        return view('admin.jadwal.edit', compact('jadwal'));
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
            'matkul' =>  'required',
            'dosen' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);

        $jadwal = Jadwal::find($id);

        $jadwal->matkul_id = $validated['matkul'];
        $jadwal->hari = $validated['hari'];
        $jadwal->jam_mulai = $validated['jam_mulai'];
        $jadwal->jam_selesai = $validated['jam_selesai'];

        $dosen = Dosen::find($validated['dosen']);
        $jadwal->schedulable()->associate($dosen);
        $jadwal->save();

        return redirect()->route('jadwal.index')->with('status', 'Jadwal berhasil di edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Jadwal::find($id)->delete();
        return redirect()->route('jadwal.index')->with('status', 'Jadwal berhasil di hapus');
    }

    public function dosen()
    {
        if (request()->ajax()) {
            $jadwalDosen = Jadwal::dosen()->where('schedulable_id', auth()->user()->authable_id)->with('matkul')->get();

            return datatables()->of($jadwalDosen)
                ->addColumn('kode_matkul', function ($jadwalDosen) {
                    return $jadwalDosen->matkul->kode;
                })
                ->addColumn('nama_matkul', function ($jadwalDosen) {
                    return $jadwalDosen->matkul->nama;
                })
                ->addColumn('jam', function ($jadwalDosen) {
                    return $jadwalDosen->jam_mulai . ' - ' . $jadwalDosen->jam_selesai;
                })
                ->addColumn('ruang', function ($jadwalDosen) {
                    return $jadwalDosen->matkul->ruang;
                })
                ->addColumn('total_peserta', function ($jadwalDosen) {
                    return Jadwal::mahasiswa()->where('matkul_id', $jadwalDosen->matkul_id)->count();
                })
                ->addColumn('action', function ($jadwalDosen) {
                    return '<a href="' . route('dosen.jadwal.pertemuan', $jadwalDosen->id) . '" data-toggle="tooltip" data-original-title="Detail Jadwal" title="Detail Jadwal" class="btn btn-info"> <span class="fas fa-fw fa-calendar-day"></span></a>';
                })
                ->only(['kode_matkul', 'nama_matkul', 'jam', 'hari', 'ruang', 'total_peserta', 'action'])
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dosen.jadwal');
    }

    public function pertemuan($id)
    {
        $jadwalDosen = Jadwal::dosen()->where('schedulable_id', auth()->user()->authable_id)->whereId($id)->with('matkul')->firstOrFail();

        if (request()->ajax()) {
            $jurnal = Jurnal::where('jadwal_id', $id)->get();

            return datatables()->of($jurnal)
                ->addColumn('action', function ($jurnal) {
                    $pertemuanSebelumnya = Jurnal::where('id', '<', $jurnal->id)->orderBy('id', 'desc')->first();
                    if ($jurnal->pertemuan !== 1) {
                        if (!empty($pertemuanSebelumnya->qrcode_token)) {
                            if (!empty($jurnal->qrcode_token)) {
                                return '<a href="' . route('dosen.jadwal.absensi.index', ['id' => $jurnal->jadwal_id, 'pertemuan' => $jurnal->pertemuan]) . '" data-toggle="tooltip" data-original-title="Absensi" title="Absensi" class="btn btn-success"> <span class="fas fa-fw fa-user-check"></span> Absensi</a>';
                            } else {
                                return '<a href="' . route('dosen.jadwal.jurnal.create', ['id' => $jurnal->jadwal_id, 'pertemuan' => $jurnal->pertemuan]) . '" data-toggle="tooltip" data-original-title="Buat Jurnal" title="Buat Jurnal" class="btn btn-info"> <span class="fas fa-fw fa-plus-square"></span> Buat Jurnal</a>';
                            }
                        } else {
                            return '<span class="label label-warning">Belum Terlaksana</span>';
                        }
                    } else {
                        if (!empty($jurnal->qrcode_token)) {
                            return '<a href="' . route('dosen.jadwal.absensi.index', ['id' => $jurnal->jadwal_id, 'pertemuan' => $jurnal->pertemuan]) . '" data-toggle="tooltip" data-original-title="Absensi" title="Absensi" class="btn btn-success"> <span class="fas fa-fw fa-user-check"></span> Absensi</a>';
                        } else {
                            return '<a href="' . route('dosen.jadwal.jurnal.create', ['id' => $jurnal->jadwal_id, 'pertemuan' => $jurnal->pertemuan]) . '" data-toggle="tooltip" data-original-title="Buat Jurnal" title="Buat Jurnal" class="btn btn-info"> <span class="fas fa-fw fa-plus-square"></span> Buat Jurnal</a>';
                        }
                    }
                })
                ->addColumn('qrcode', function ($jurnal) {
                    if (!empty($jurnal->qrcode_token)) {
                        return '<a href="' . route('dosen.jadwal.jurnal.qrcode', ['id' => $jurnal->jadwal_id, 'pertemuan' => $jurnal->pertemuan]) . '" data-toggle="tooltip" data-original-title="QR Code" title="QR Code" class="btn bg-navy"> <i class="fas fa-2x fa-qrcode"></i></a>';
                    } else {
                        return '-';
                    }
                })
                ->editColumn('materi', function ($jurnal) {
                    if (!empty($jurnal->materi)) {
                        return $jurnal->materi;
                    } else {
                        return '-';
                    }
                })
                ->editColumn('keterangan', function ($jurnal) {
                    if (!empty($jurnal->keterangan)) {
                        return $jurnal->keterangan;
                    } else {
                        return '-';
                    }
                })
                ->only(['materi', 'keterangan', 'pertemuan', 'qrcode', 'action'])
                ->rawColumns(['qrcode', 'action'])
                ->make(true);
        }

        return view('dosen.pertemuan', ['jadwal' => $jadwalDosen]);
    }

    public function getDatatables()
    {
        if (request()->ajax()) {
            $dosen = Jadwal::dosen()->with('matkul')->get();

            return datatables()->of($dosen)
                ->addColumn('kode_matkul', function ($dosen) {
                    return $dosen->matkul->kode;
                })
                ->addColumn('nama_matkul', function ($dosen) {
                    return $dosen->matkul->nama;
                })
                ->addColumn('dosen_pengajar', function ($dosen) {
                    return $dosen->schedulable->nama;
                })
                ->addColumn('jam', function ($dosen) {
                    return $dosen->jam_mulai . ' - ' . $dosen->jam_selesai;
                })
                ->addColumn('ruang', function ($dosen) {
                    return $dosen->matkul->ruang;
                })
                ->addColumn('total_peserta', function ($dosen) {
                    return Jadwal::mahasiswa()->where('matkul_id', $dosen->matkul_id)->count();
                })
                ->addColumn('action', function ($dosen) {
                    return '<form action="' . route('jadwal.destroy', $dosen->id) . '" method="POST"> <a href="' . route('jadwal.show', $dosen->id) . '" data-toggle="tooltip" data-original-title="Detail Jadwal" title="Detail Jadwal" class="btn btn-info"> <span class="fas fa-fw fa-calendar-day"></span></a> <a href="' . route('jadwal.edit', $dosen->id) . '" data-toggle="tooltip" data-original-title="Edit" title="Edit" class="btn btn-success"><span class="fas fa-fw fa-edit"></span></a> ' . csrf_field() . ' ' . method_field("DELETE") . ' <button type="submit" class="btn btn-danger" onclick="return confirm(\'Apakah anda yakin ingin menghapusnya?\')" data-toggle="tooltip" data-original-title="Hapus" title="Hapus"><span class="fas fa-fw fa-trash-alt"></span></button> </form>';
                })
                ->only(['kode_matkul', 'nama_matkul', 'dosen_pengajar', 'jam', 'hari', 'ruang', 'total_peserta', 'action'])
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getDatatablesMahasiswa($matkul_id)
    {
        if (request()->ajax()) {
            $mahasiswa = Jadwal::mahasiswa()->with('schedulable.authInfo')->where('matkul_id', $matkul_id)->get();

            return datatables()->of($mahasiswa)
                ->addColumn('nomor_induk', function ($mahasiswa) {
                    return $mahasiswa->schedulable->authInfo->username;
                })
                ->addColumn('nama', function ($mahasiswa) {
                    return $mahasiswa->schedulable->nama;
                })
                ->addColumn('jenis_kelamin', function ($mahasiswa) {
                    return $mahasiswa->schedulable->jenis_kelamin;
                })
                ->editColumn('photo', function ($mahasiswa) {
                    return '<img class="zoom" src="' . asset('vendor/images/users') . '/' . $mahasiswa->schedulable->photo . '" alt="' . $mahasiswa->schedulable->nama . '" width="100px">';
                })
                ->addColumn('action', function ($mahasiswa) {
                    return '<button id="delete-mahasiswa" type="button" data-id="' . $mahasiswa->schedulable->id . '" class="btn btn-danger" data-toggle="tooltip" data-original-title="Hapus" title="Hapus"><span class="fas fa-fw fa-trash-alt"></span></button>';
                })

                ->only(['nomor_induk', 'nama', 'jenis_kelamin', 'photo', 'action'])
                ->rawColumns(['action', 'photo'])
                ->make(true);
        }
    }

    public function addJadwalMahasiswa(Request $request, $jadwal_id)
    {
        $jadwal = Jadwal::find($jadwal_id);

        $validated = $request->validate([
            'mahasiswa' =>  'required',
        ]);

        $checkMahasiswaIsExists = Jadwal::where('schedulable_id', $validated['mahasiswa'])->where('schedulable_type', 'App\Mahasiswa')->where('matkul_id', $jadwal->matkul_id)->exists();
        if ($checkMahasiswaIsExists) {
            return response()->json(['errors' => ['mahasiswa' => ['Mahasiswa sudah terdaftar di jadwal ini']]], 422);
        }

        $mahasiswa = Mahasiswa::find($validated['mahasiswa']);

        $hari = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jum\'at',
            'Sabtu',
            'Minggu',
        ];
        $convertHari = array_search($jadwal->hari, $hari) + 1;

        $jadwalMahasiswa = $mahasiswa->schedules()->create([
            'matkul_id' => $jadwal->matkul_id,
            'hari' => $convertHari,
            'jam_mulai' => $jadwal->jam_mulai,
            'jam_selesai' => $jadwal->jam_selesai,
        ]);

        return response()->json(['message' => 'Mahasiswa berhasil di tambah ke jadwal']);
    }

    public function deleteJadwalMahasiswa($id, $mahasiswa)
    {
        $mahasiswa = Mahasiswa::find($mahasiswa);
        $mahasiswa->schedules()->delete();

        return response()->json(['message' => 'Mahasiswa berhasil di hapus dari jadwal']);
    }
}
