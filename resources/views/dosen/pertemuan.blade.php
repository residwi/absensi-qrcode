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
                    <div class="col-sm-4">
                        <ul class="list-unstyled">
                            <li class="margin-bottom"><strong><i class="fas fa-fw fa-book"></i> Mata Kuliah</strong>
                            </li>
                            <li><b>Kode</b> : {{ $jadwal->matkul->kode }}</li>
                            <li><b>Nama</b> : {{ $jadwal->matkul->nama }}</li>
                        </ul>
                    </div>
                    <div class="col-sm-4">
                        <ul class="list-unstyled">
                            <li class="margin-bottom"><strong><i class="fas fa-fw fa-user-tie"></i> Dosen
                                    Pengajar</strong></li>
                            <li><b>Kode</b> : {{ $jadwal->schedulable->authInfo->username }}</li>
                            <li><b>Nama</b> : {{ $jadwal->schedulable->nama }}</li>
                        </ul>
                    </div>
                    <div class="col-sm-4">
                        <ul class="list-unstyled">
                            <li class="margin-bottom"><strong><i class="fas fa-fw fa-clock"></i> Hari & Jam</strong>
                            </li>
                            <li><b>Jam</b> : {{ $jadwal->jam_mulai . ' - ' . $jadwal->jam_selesai }}</li>
                            <li><b>Hari</b> : {{ $jadwal->hari }}</li>
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
                            <th>QR Code Absen</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@if (session('status'))
<div class="modal fade in" id="modal-default" style="display: block; padding-right: 17px;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Informasi</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success">
                    <h4><i class="icon fas fa-fw fa-check"></i> Success</h4>
                    {{ session('status') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endif
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
                ajax: '{{ route("dosen.jadwal.pertemuan", $jadwal->id) }}',
                columns: [
                    { data: 'pertemuan' },
                    { data: 'materi' },
                    { data: 'keterangan' },
                    { data: 'qrcode' },
                    { data: 'action' },
                ]
            });
            
        @if(session('status'))
                $('#modal-default').modal();
        @endif
    });
</script>
@stop