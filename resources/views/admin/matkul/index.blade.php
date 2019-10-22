@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Daftar Matkul - ')

@section('content_header')
<h1>Daftar Mata Kuliah</h1>

{{ Breadcrumbs::render('matkul.index') }}
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
            width: 20%;
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
                <h3 class="box-title">Tabel Mata Kuliah</h3>
                <a class="btn btn-primary pull-right"  href="{{ route('matkul.create') }}">
                    <span class="fas fa-fw fa-plus"></span> Tambah Mata Kuliah
                </a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="daftar-matkul" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Kode Matkul</th>
                            <th>Nama Matkul</th>
                            <th>Ruang</th>
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
        $('#daftar-matkul').DataTable({
                columnDefs: [{
                        searchable: false,
                        targets: [-1]
                    },
                    {
                        orderable: false,
                        targets: [-1]
                    },
                    { 
                        className: "text-center",
                        targets: [-1] 
                    },
                    
                ],
                pageLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                processing: true,
                serverSide: true,
                order: [
                    [0, 'desc']
                ],
                ajax: '{{ route("admin.datatables.matkul") }}',
                columns: [
                    { data: 'kode' },
                    { data: 'nama' },
                    { data: 'ruang' },
                    { data: 'action' },
                ]
            });
        
        @if(session('status'))
                $('#modal-default').modal();
        @endif
    });
</script>
@stop