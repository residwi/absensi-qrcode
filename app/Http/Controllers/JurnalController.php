<?php

namespace App\Http\Controllers;

use App\Absensi;
use App\Jadwal;
use App\Jurnal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JurnalController extends Controller
{

    public function create($id_jadwal, $pertemuan)
    {
        $jadwalDosen = Jadwal::dosen()->whereId($id_jadwal)->with('matkul')->firstOrFail();

        $pertemuanSebelumnya = Jurnal::where('pertemuan', '<', $pertemuan)->orderBy('id', 'desc')->first();
        if (!empty($pertemuanSebelumnya->qrcode_token) || $pertemuan == 1) {
            $jurnal = Jurnal::where('jadwal_id', $id_jadwal)->where('pertemuan', $pertemuan)->firstOrFail();

            return view('dosen.jurnal', ['jurnal' => $jurnal, 'jadwal' => $jadwalDosen]);
        }

        abort(404);
    }

    public function store(Request $request, $id, $pertemuan)
    {
        $validated = $request->validate([
            'materi' => 'required',
            'keterangan' => 'required',
        ]);

        $jurnal = Jurnal::where('jadwal_id', $id)->where('pertemuan', $pertemuan);

        $jurnal->update([
            'materi' => $validated['materi'],
            'keterangan' => $validated['keterangan'],
        ]);

        // menyimpan qrcode_token ke semua pertemuan pada mata kuliah tersebut
        $absen = Jurnal::where('jadwal_id', $id);
        $absen->update(['qrcode_token' => Str::random(64)]);

        // matkul yang diajar dosen
        $pertemuan = $jurnal->with(['jadwal' => function ($query) {
            $query->where('schedulable_type', 'App\Dosen');
        }])->first();

        // mahasiswa yang mengikuti pertemuan
        $mahasiswa = Jadwal::where('schedulable_type', 'App\Mahasiswa')->where('matkul_id', $pertemuan->jadwal->matkul_id)->get();

        // tambah mahasiswa ke absen pertemuan
        $absenMahasiswa = [];
        for ($i = 0; $i < count($mahasiswa->pluck('schedulable_id')); $i++) {
            array_push($absenMahasiswa, new Absensi(['mahasiswa_id' => $mahasiswa[$i]->schedulable_id]));
        }

        $pertemuan->absensi()->saveMany($absenMahasiswa);

        return redirect()->route('jadwal.pertemuan', ['id' => $id])->with('status', 'Jurnal berhasil di buat, silahkan melakukan absensi');
    }

    public function qrcode($id)
    {
        $jurnal = Jurnal::where('jadwal_id', $id)->with('jadwal.matkul')->firstOrFail();
        if (!empty($jurnal->qrcode_token)) {
            $qrcode = \QrCode::format('png')
                ->merge('vendor/images/logo.png', 0.1, true)
                ->size(500)->errorCorrection('H')
                ->generate($jurnal->qrcode_token);
            $image = 'qrcode/' . $jurnal->jadwal->matkul->kode . '-' . $jurnal->jadwal->matkul->nama . '.png';

            if (!file_exists(storage_path('app/' . $image))) {
                \Storage::disk('local')->put($image, $qrcode);
            }

            return response()->download(storage_path('app/' . $image));
        }

        abort('404');
    }
}
