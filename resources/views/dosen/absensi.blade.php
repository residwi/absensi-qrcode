@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Absensi - ')

@section('content_header')
<h1>Absensi pertemuan ke - {{ $jurnal->pertemuan }}</h1>

{{ Breadcrumbs::render('absensi', $jadwal, $jurnal) }}
@stop

@section('css')
<style>
    table thead th:first-child {
        width: 10%;
    }

    table thead th:nth-child(3) {
        width: 5%;
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

<!-- table mahasiswa -->
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <table id="absensi-mahasiswa" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nomor Induk</th>
                            <th>Nama Mahasiswa</th>
                            <th>Jenis Kelamin</th>
                            <th>Foto</th>
                            <th>Status</th>
                            <th>Jam Absen</th>
                            <th>Validasi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit-absensi">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ubah Status Absensi Mahasiswa</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-default" style="display:none">
                    <ul class="list-unstyled"></ul>
                </div>
                <form id="form-validasi-absensi" role="form">
                    @csrf
                    <div class="form-group">
                        <label for="mahasiswa">Status Absen</label>
                        <select style="width: 100%;" id="mahasiswa" class="form-control" name="mahasiswa">
                            <option value="1">Tidak Hadir</option>
                            <option value="2">Hadir</option>
                            <option value="3">Sakit</option>
                            <option value="4">Izin</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@stop

@section('js')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#absensi-mahasiswa').DataTable({
                columnDefs: [{
                        searchable: false,
                        targets: [3, -1]
                    },
                    {
                        orderable: false,
                        targets: [3, -1]
                    },
                    { 
                        className: "text-center",
                        targets: [3, -3, -2, -1] 
                    }
                ],
                pageLength: 50,
                lengthMenu: [10, 25, 50, 75, 100],
                processing: false,
                serverSide: true,
                order: [
                    [0, 'desc']
                ],
                ajax: '{{ route("dosen.jadwal.absensi.datatables", ['id' => $jurnal->id, 'pertemuan' => $jurnal->pertemuan]) }}',
                columns: [
                    { data: 'nomor_induk' },
                    { data: 'nama' },
                    { data: 'jenis_kelamin' },
                    { data: 'photo' },
                    { data: 'status' },
                    { data: 'jam_absen' },
                    { data: 'action' },
                ]
            });

        $('#mahasiswa').on('change',function() {
            var mahasiswa =  $('#edit-absensi').data('id');
            var value = $(this).val();
            $.ajax({
                type: 'POST',
                url: '{{ route("dosen.jadwal.absensi.update", ['id' => $jurnal->id, 'pertemuan' => $jurnal->pertemuan]) }}',
                data:{
                    mahasiswa: mahasiswa,
                    status: value
                },
                success: function(data){
                    toastr.success(data.message);
                    var table = $('#absensi-mahasiswa').dataTable();
                    table.fnDraw(false);
                },
                error:function(exception){
                    console.log('Error:', data);
                }
            });
        });

        var text2 = $('#edit-absensi').data('status');
        $("#mySelect2 option").filter(function() {
            return this.text == text2; 
        }).attr('selected', true);

        setInterval(function(){
            var table = $('#absensi-mahasiswa').dataTable();
            table.fnDraw(false);
            console.log('refreshed');
        }, 10000);
    });
</script>
@stop