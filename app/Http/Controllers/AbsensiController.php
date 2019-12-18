<?php

namespace App\Http\Controllers;

use App\Absensi;
use App\Jadwal;
use App\Jurnal;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index($id, $pertemuan)
    {
        // menampilkan absensi
        $jurnal = Jurnal::with('absensi')->where('jadwal_id', $id)->where('pertemuan', $pertemuan)->firstOrFail();

        // menampilkan table pertemuan pada jadwal dosen untuk admin
        if (auth()->user()->getRole() == 'admin') {
            $jadwal = Jadwal::dosen()->whereId($id)->with('matkul')->firstOrFail();
        } else {
            // menampilkan table pertemuan pada jadwal dosen yang sedang login
            $jadwal = Jadwal::dosen()->where('schedulable_id', auth()->user()->authable_id)->whereId($id)->with('matkul')->firstOrFail();
        }

        return view('dosen.absensi', compact(['jadwal', 'jurnal']));
    }

    public function getDatatables($jurnal_id)
    {
        if (request()->ajax()) {
            // menampilkan daftar mahasiswa yang mengikuti pertemuan
            $absensi = Absensi::with('mahasiswa.authInfo')->where('jurnal_id', $jurnal_id)->get();

            return datatables()->of($absensi)
                ->editColumn('photo', function ($absensi) {
                    return '<img class="zoom" src="' . asset('vendor/images/users') . '/' . $absensi->mahasiswa->photo . '" alt="' . $absensi->mahasiswa->nama . '" width="100px">';
                })
                ->addColumn('nomor_induk', function ($absensi) {
                    return $absensi->mahasiswa->authInfo->username;
                })
                ->addColumn('nama', function ($absensi) {
                    return $absensi->mahasiswa->nama;
                })
                ->addColumn('jenis_kelamin', function ($absensi) {
                    return $absensi->mahasiswa->jenis_kelamin;
                })
                ->addColumn('jam_absen', function ($absensi) {
                    return $absensi->jam_absen ?: '-';
                })
                ->addColumn('status', function ($absensi) {
                    switch ($absensi->status) {
                        case 'Hadir':
                            return '<span class="label label-success">' . $absensi->status . '</span>';
                            break;

                        case 'Izin':
                            return '<span class="label label-info">' . $absensi->status . '</span>';
                            break;

                        case 'Sakit':
                            return '<span class="label label-warning">' . $absensi->status . '</span>';
                            break;

                        default:
                            return '<span class="label label-default">' . $absensi->status . '</span>';
                            break;
                    }
                })
                ->addColumn('action', function ($absensi) {
                    return '<button type="button" class="btn btn-primary edit-absensi" data-toggle="modal" data-id="' . $absensi->mahasiswa_id . '" data-status="' . $absensi->status . '" data-target="#modal-edit-absensi" title="Ubah status"><span class="fas fa-fw fa-edit"></span></button>';
                })
                ->only(['nomor_induk', 'nama', 'jenis_kelamin', 'jam_absen', 'photo', 'status', 'action'])
                ->rawColumns(['photo', 'status', 'action'])
                ->make(true);
        }
    }

    public function updateStatus($id, $pertemuan, Request $request)
    {
        // validasi status absen
        if ($request->ajax()) {
            $validated = $request->validate([
                'mahasiswa' => 'required',
                'status' => 'required'
            ]);

            $absensi = Absensi::where('jurnal_id', $pertemuan)->where('mahasiswa_id', $validated['mahasiswa']);
            $absensi->update([
                'status' => $validated['status'],
                'jam_absen' => now()
            ]);

            return response()->json(['message' => 'Status Absen Mahasiswa berhasil di ubah']);
        }
    }
}
