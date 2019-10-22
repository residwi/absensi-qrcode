@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Detail Mahasiswa - ')

@section('content_header')
<h1>Detail Mahasiswa</h1>

{{ Breadcrumbs::render('mahasiswa.show', $mahasiswa) }}
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                {{-- <h3 class="box-title">Tambah Mahasiswa</h3> --}}
                <a class="btn btn-warning" href="{{ route('mahasiswa.edit', $mahasiswa->username) }}">
                    <span class="fas fa-fw fa-edit"></span> Edit Mahasiswa
                </a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form role="form">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="nomor_induk">Nomor Induk</label>
                            <input type="text" class="form-control" value="{{ $mahasiswa->username }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for=" nama">Nama</label>
                            <input type="text" class="form-control" value="{{ $mahasiswa->authable->nama }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <input type="text" class="form-control" value="{{ $mahasiswa->authable->jenis_kelamin }}"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" value="{{ $mahasiswa->authable->alamat }}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fas fa-fw fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" name="tanggal_lahir"
                                    value="{{ $mahasiswa->authable->tanggal_lahir }}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for=" email">Email address</label>
                            <input type="email" class="form-control" value="{{ $mahasiswa->authable->email }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No Telp</label>
                            <input type="text" class="form-control" value="{{ $mahasiswa->authable->no_telp }}"
                                readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="photo">Foto Mahasiswa</label>
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="{{ asset('vendor/images/users/'.$mahasiswa->authable->photo) }}"
                                        alt="{{ $mahasiswa->authable->nama }}" class="img-fluid" width="150px">
                                </div>
                                {{-- <div class="col-md-10">
                                    Gambar Profile Anda sebaiknya tidak lebih dari 2MB.
                                    <input type="file" name="photo" title="Change Avatar"
                                        data-filename-placement="inside">
                                </div> --}}
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </form>
            </div>
        </div>
    </div>
</div>
@stop

