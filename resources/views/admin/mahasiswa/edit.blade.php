@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Edit Mahasiswa - ')

@section('content_header')
<h1>Edit Mahasiswa</h1>

{{ Breadcrumbs::render('mahasiswa.edit', $mahasiswa) }}
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <form role="form" method="POST" action="{{ route('mahasiswa.update', $mahasiswa->username) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="box-body">
                        <div class="form-group has-feedback {{ $errors->has('photo') ? 'has-error' : '' }}">
                            <label class="control-label " for="photo">Foto Diri</label>
                            <div class="row">
                                <div class="col-md-2">
                                    <img src="{{ asset('vendor/images/users/'.$mahasiswa->authable->photo) }}" alt="{{ $mahasiswa->authable->nama }}"
                                        class="img-fluid" width="150px">
                                </div>
                                <div class="col-md-10">
                                    Gambar Profile Anda sebaiknya tidak lebih dari 2MB.
                                    <input type="file" name="photo" title="Change Avatar"
                                        data-filename-placement="inside">
                                </div>
                            </div>
                            @if ($errors->has('photo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('photo') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('nomor_induk') ? 'has-error' : '' }}">
                            <label for="nomor_induk">Nomor Induk</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control" id="nomor_induk" name="nomor_induk"
                                placeholder="Nomor Induk Mahasiswa" value="{{ $mahasiswa->username }}" readonly>
                            @if ($errors->has('nomor_induk'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nomor_induk') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('nama') ? 'has-error' : '' }}">
                            <label for="nama">Nama</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama"
                                value="{{ $mahasiswa->authable->nama }}" required>
                            @if ($errors->has('nama'))
                            <span class="help-block">
                                <strong>{{ $errors->first('nama') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('jenis_kelamin') ? 'has-error' : '' }}">
                            <label for="jenis_kelamin1">Jenis Kelamin</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="jenis_kelamin" id="jenis_kelamin1" value="1"
                                        {{ $mahasiswa->authable->jenis_kelamin == 'Laki-Laki' ? 'checked' : '' }}>
                                    Laki-Laki
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="jenis_kelamin" id="jenis_kelamin2" value="2"
                                        {{ $mahasiswa->authable->jenis_kelamin == 'Perempuan' ? 'checked' : '' }}>
                                    Perempuan
                                </label>
                            </div>
                            @if ($errors->has('jenis_kelamin'))
                            <span class="help-block">
                                <strong>{{ $errors->first('jenis_kelamin') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat"
                                value="{{ $mahasiswa->authable->alamat }}">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fas fa-fw fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker"
                                    placeholder="08/17/1945" name="tanggal_lahir"
                                    value="{{ $mahasiswa->authable->tanggal_lahir }}">
                            </div>
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label for="email">Email address</label><span class="text-danger"> *</span>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email"
                                value="{{ $mahasiswa->authable->email }}" required>
                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No Telp</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="No Telp"
                                value="{{ $mahasiswa->authable->no_telp }}">
                        </div>
                        <div style="margin-top: 50px">
                            <h4>Ubah Password</h4>
                            <hr>
                        </div>
                        <div class="alert alert-danger">
                            Isi form dibawah ini hanya bila Anda hendak mengubah password
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Change Password">
                            @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div
                            class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" placeholder="Verify New Password">
                            @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <br><br>
                        <small><span class="text-danger">*</span> Wajib Diisi</small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop



@section('js')
<script>
    $(document).ready(function() {
        //Date picker
        $('#datepicker').datepicker({
            autoclose: true
        })
    });
</script>
@stop