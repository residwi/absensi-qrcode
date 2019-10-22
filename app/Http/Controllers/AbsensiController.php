<?php

namespace App\Http\Controllers;

use App\Absensi;
use App\Jadwal;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index($id, $pertemuan)
    {
        $jadwal = Jadwal::dosen()->where('schedulable_id', auth()->user()->authable_id)->whereId($id)->with('matkul')->firstOrFail();
        $absensi = Absensi::with('jurnal:id,jadwal_id,pertemuan')->first();

        return view('dosen.absensi', compact(['jadwal', 'absensi']));
    }

    public function getDatatables()
    {
        if (request()->ajax()) {
            $absensi = Absensi::with('mahasiswa.authInfo')->get();

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
                    return '<button id="edit-absensi" type="button" class="btn btn-primary" data-toggle="modal" data-id="' . $absensi->mahasiswa_id . '" data-status="' . $absensi->status . '" data-target="#modal-edit-absensi" title="Ubah status"><span class="fas fa-fw fa-edit"></span></button>';
                })
                ->only(['nomor_induk', 'nama', 'jenis_kelamin', 'jam_absen', 'photo', 'status', 'action'])
                ->rawColumns(['photo', 'status', 'action'])
                ->make(true);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        if ($request->ajax()) {
            $validated = $request->validate([
                'mahasiswa' => 'required',
                'status' => 'required'
            ]);

            $absensi = Absensi::where('jurnal_id', $id)->where('mahasiswa_id', $validated['mahasiswa']);
            $absensi->update([
                'status' => $validated['status'],
                'jam_absen' => now()
            ]);

            return response()->json(['message' => 'Status Absen Mahasiswa berhasil di ubah']);
        }
    }
}
