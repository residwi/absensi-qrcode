@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Jadwal Dosen - ')

@section('content_header')
<h1>Jadwal Dosen</h1>

{{ Breadcrumbs::render('jadwaldosen') }}
@stop

@section('css')
<style>
    table thead th:first-child {
        width: 10%;
    }

    table thead th:nth-child(6) {
        width: 10%;
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
            <div class="box-header">
                <h3 class="box-title">Jadwal Dosen</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="daftar-jadwal" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Kode Matkul</th>
                            <th>Nama Matkul</th>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Ruang</th>
                            <th>Total Peserta</th>
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
        $('#daftar-jadwal').DataTable({
                columnDefs: [{
                        searchable: false,
                        targets: [0, 1, 3, -1]
                    },
                    {
                        orderable: false,
                        targets: [0, 1, 3, -1]
                    },
                    { 
                        className: "text-center",
                        targets: [5, -1] 
                    }
                ],
                pageLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                processing: true,
                serverSide: true,
                order: [
                    [2, 'desc']
                ],
                ajax: '{{ route("jadwal.dosen.index") }}',
                columns: [
                    { data: 'kode_matkul' },
                    { data: 'nama_matkul' },
                    { data: 'hari' },
                    { data: 'jam' },
                    { data: 'ruang' },
                    { data: 'total_peserta' },
                    { data: 'action' },
                ]
            });
        
        @if (session('status'))
            toastr.success('{{ session('status') }}');
        @endif
    });
</script>
@stop