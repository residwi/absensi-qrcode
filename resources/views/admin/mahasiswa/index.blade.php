@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Daftar Mahasiswa - ')

@section('content_header')
<h1>Daftar Mahasiswa</h1>

{{ Breadcrumbs::render('mahasiswa.index') }}
@stop

@section('css')
    <style>
        table thead th:first-child {
            width: 10%;
        }
        table thead th:nth-child(2) {
            width: 20%;
        }
        table thead th:nth-child(3) {
            width: 7%;
        }
        table thead th:nth-child(4) {
            width: 15%;
        }
        table thead th:nth-child(5) {
            width: 15%;
        }
        table thead th:last-child{
            width: 20%;
        }
    </style>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tabel Mahasiswa</h3>
                <a class="btn btn-primary pull-right"  href="{{ route('mahasiswa.create') }}">
                    <span class="fas fa-fw fa-plus"></span> Tambah Mahasiswa
                </a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="daftar-mahasiswa" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nomor Induk</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                            <th>Foto</th>
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
        $('#daftar-mahasiswa').DataTable({
                columnDefs: [{
                        searchable: false,
                        targets: [2, 3, 4, -1]
                    },
                    {
                        orderable: false,
                        targets: [2, 3, 4, -1]
                    },
                    { 
                        className: "text-center",
                        targets: [4, -1] 
                    },
                    
                ],
                pageLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                processing: true,
                serverSide: true,
                order: [
                    [1, 'desc']
                ],
                ajax: '{{ route("admin.datatables.mahasiswa") }}',
                columns: [
                    { data: 'username' },
                    { data: 'nama' },
                    { data: 'jenis_kelamin' },
                    { data: 'alamat' },
                    { data: 'photo' },
                    { data: 'action' },
                ]
            });
        
        @if(session('status'))
                $('#modal-default').modal();
        @endif
    });
</script>
@stop