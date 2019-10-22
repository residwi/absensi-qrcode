@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Detail Jadwal - ')

@section('content_header')
<h1>Detail Jadwal</h1>

{{ Breadcrumbs::render('jadwal.show', $jadwal) }}
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
                            <li class="margin-bottom"><strong><i class="fas fa-fw fa-clock"></i> Pertemuan</strong>
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
            <div class="box-header">
                <h3 class="box-title">Daftar Mahasiswa</h3>
                <button type="button" class="btn btn-primary pull-right" data-toggle="modal"
                    data-target="#modal-tambah-mahasiswa">
                    <span class="fas fa-fw fa-plus"></span> Tambah Mahasiswa
                </button>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="daftar-jadwalMahasiswa" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nomor Induk</th>
                            <th>Nama Mahasiswa</th>
                            <th>Jenis Kelamin</th>
                            <th>Foto</th>
                            <th>Opsi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-tambah-mahasiswa">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tambah Mahasiswa ke Jadwal</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger print-error-msg" style="display:none">
                    <ul class="list-unstyled"></ul>
                </div>
                <form id="form-addMahasiswa" role="form">
                    @csrf
                    <div class="form-group">
                        <label for="mahasiswa">NIM/Nama Mahasiswa</label>
                        <select style="width: 100%;" id="mahasiswa" class="form-control" name="mahasiswa"
                            data-placeholder="Cari Mahasiswa">
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <button id="tambah-mahasiswa" type="button" class="btn btn-primary">Tambah</button>
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

        $('#daftar-jadwalMahasiswa').DataTable({
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
                        targets: [3, -1] 
                    }
                ],
                pageLength: 10,
                lengthMenu: [10, 25, 50, 75, 100],
                processing: true,
                serverSide: true,
                order: [
                    [0, 'desc']
                ],
                ajax: '{{ route("admin.datatables.jadwalmahasiswa") }}',
                columns: [
                    { data: 'nomor_induk' },
                    { data: 'nama' },
                    { data: 'jenis_kelamin' },
                    { data: 'photo' },
                    { data: 'action' },
                ]
            });

            $("#mahasiswa").select2({
            ajax: {
                url: '{{ route("admin.ajaxsearch.mahasiswa") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.username + ' - ' + obj.nama
                            };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            placeholder: function(){
                $(this).data('placeholder');
            },
            templateResult: ResultTemplater,
            templateSelection: SelectionTemplater
        });

        function ResultTemplater(item) {        
            if (item.loading) {
                return item.text;
            }

            return item.text;
        }

        function SelectionTemplater(item) {
            if(typeof item.id !== "undefined") {
                return ResultTemplater(item);
            }

            return item.id
        }

        $('#tambah-mahasiswa').click(function(e){
            e.preventDefault();
            $('#tambah-mahasiswa').html('Sending..');
    
            $.ajax({
              data: $('#form-addMahasiswa').serialize(),
              url: "{{ route('admin.jadwal.mahasiswa.add', $jadwal->id) }}",
              type: "POST",
              dataType: 'json',
              success: function (data) {
                  $('#tambah-mahasiswa').html('Tambah');
                  $('#form-addMahasiswa').trigger("reset");
                  $('#modal-tambah-mahasiswa').modal('hide');
                  var table = $('#daftar-jadwalMahasiswa').dataTable();
                  table.fnDraw(false);
                  toastr.success(data.message);
              },
              error: function (data) {
                console.log('Error:', data);
                $.each(data.responseJSON.errors, function(key, value) {
                    toastr.error(value);
                });
                $('#tambah-mahasiswa').html('Tambah');
              }
            });
        });
    
        function printErrorMsg (msg) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','block');
            $.each(msg, function(key, value) {
                $(".print-error-msg").find("ul").append('<li>'+value+'</li>');
            });

        }

        $('body').on('click', '#delete-mahasiswa', function () {
            var mahasiswa_id = $(this).data("id");
            confirm("Apakah anda yakin ingin menghapusnya ?");

            $.ajax({
                type: "DELETE",
                url: location.href + "/mahasiswa/delete/" + mahasiswa_id,
                success: function (data) {
                    toastr.success(data.message);
                    var table = $('#daftar-jadwalMahasiswa').dataTable();
                    table.fnDraw(false);
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });
    });
</script>
@stop