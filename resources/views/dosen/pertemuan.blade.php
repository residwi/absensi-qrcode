@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Jadwal Pertemuan - ')

@section('content_header')
<h1>Jadwal Pertemuan</h1>

{{ Breadcrumbs::render('pertemuan', $jadwal) }}
@stop

@section('css')
<style>
    table thead th:nth-child(1) {
        width: 5%;
    }

    table thead th:nth-child(2) {
        width: 20%;
    }

    table thead th:nth-child(3) {
        width: 30%;
    }

    table thead th:nth-child(4) {
        width: 15%;
    }

    table thead th:last-child {
        width: 10%;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row margin">
                    <div class="col-sm-3">
                        <ul class="list-unstyled">
                            <li class="margin-bottom"><strong><i class="fas fa-fw fa-book"></i> Mata Kuliah</strong>
                            </li>
                            <li><b>Kode</b> : {{ $jadwal->matkul->kode }}</li>
                            <li><b>Nama</b> : {{ $jadwal->matkul->nama }}</li>
                        </ul>
                    </div>
                    <div class="col-sm-3">
                        <ul class="list-unstyled">
                            <li class="margin-bottom"><strong><i class="fas fa-fw fa-user-tie"></i> Dosen
                                    Pengajar</strong></li>
                            <li><b>Kode</b> : {{ $jadwal->schedulable->authInfo->username }}</li>
                            <li><b>Nama</b> : {{ $jadwal->schedulable->nama }}</li>
                        </ul>
                    </div>
                    <div class="col-sm-3">
                        <ul class="list-unstyled">
                            <li class="margin-bottom"><strong><i class="fas fa-fw fa-clock"></i> Hari & Jam</strong>
                            </li>
                            <li><b>Jam</b> : {{ $jadwal->jam_mulai . ' - ' . $jadwal->jam_selesai }}</li>
                            <li><b>Hari</b> : {{ $jadwal->hari }}</li>
                        </ul>
                    </div>
                    <div class="col-sm-3">
                        <ul class="list-unstyled">
                            <li class="margin-bottom"><strong><i class="fas fa-fw fa-qrcode"></i> QR Code Absen</strong>
                            </li>
                            @if ($qrcode_downloaded)
                                <li><a href="{{ route('jadwal.jurnal.qrcode', $jadwal->id) }}" type="button" class="btn btn-success"><i class="fas fa-fw fa-download"></i> Download</a></li>
                            @else
                                <li><span class="bg-danger">Silahkan buat jurnal terlebih dahulu</span></li> 
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- table jadwal -->
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <table id="daftar-pertemuan" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Pertemuan</th>
                            <th>Materi</th>
                            <th>Keterangan</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@stop



@section('js')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#daftar-pertemuan').DataTable({
                columnDefs: [{
                        searchable: false,
                        targets: [3, -1]
                    },
                    {
                        orderable: false,
                        targets: [0, 1, 2, 3, -1]
                    },
                    { 
                        className: "text-center",
                        targets: [0, 3, -1] 
                    }
                ],
                pageLength: 20,
                lengthMenu: [20],
                processing: true,
                serverSide: true,
                order: [
                    [0, 'asc']
                ],
                ajax: '{{ route("jadwal.pertemuan", $jadwal->id) }}',
                columns: [
                    { data: 'pertemuan' },
                    { data: 'materi' },
                    { data: 'keterangan' },
                    { data: 'action' },
                ]
            });
            
        @if (session('status'))
            toastr.success('{{ session('status') }}');
        @endif
    });
</script>
@stop