@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Profile - ')

@section('content_header')
<h1>Profile</h1>

{{ Breadcrumbs::render('profile') }}
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">

            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form id="form-update-profile" role="form" method="POST" action="{{ route('profile.users.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="box-body">
                        <div class="form-group has-feedback {{ $errors->has('photo') ? 'has-error' : '' }}">
                            <label class="control-label " for="photo">Foto Diri</label>
                            <div class="row">
                                <div class="col-md-2">
                                    <img id="foto-user" src="{{ asset('vendor/images/users/'.$user->authable->photo) }}"
                                        alt="{{ $user->authable->nama }}" class="img-fluid" width="150px">
                                </div>
                                <div class="col-md-10">
                                    Gambar Profile Anda sebaiknya tidak lebih dari 2MB.
                                    <input type="file" name="photo" id="photo" title="Change Avatar"
                                        data-filename-placement="inside">
                                </div>
                            </div>
                            @if ($errors->has('photo'))
                            <span class="help-block">
                                <strong>{{ $errors->first('photo') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('nama') ? 'has-error' : '' }}">
                            <label for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama"
                                value="{{ $user->authable->nama }}" required>
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
                                        {{ $user->authable->jenis_kelamin == 'Laki-Laki' ? 'checked' : '' }}>
                                    Laki-Laki
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="jenis_kelamin" id="jenis_kelamin2" value="2"
                                        {{ $user->authable->jenis_kelamin == 'Perempuan' ? 'checked' : '' }}>
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
                                value="{{ $user->authable->alamat }}">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fas fa-fw fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker"
                                    placeholder="08/17/1945" name="tanggal_lahir"
                                    value="{{ $user->authable->tanggal_lahir }}">
                            </div>
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email"
                                value="{{ $user->authable->email }}" required>
                            @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="no_telp">No Telp</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" placeholder="No Telp"
                                value="{{ $user->authable->no_telp }}">
                        </div>
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" id="update-profile" class="btn btn-primary">Submit</button>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#form-update-profile').on('submit', function(e){
            e.preventDefault();
            $('#update-profile').html('Sending..');

            $.ajax({
                data: new FormData(this),
                url: "{{ route('profile.users.update') }}",
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#update-profile').html('Submit');
                    toastr.success(data.message);
                    $('#photo').val('');
                    if (typeof  data.image !== 'undefined') {
                        $("#foto-user").attr("src", location.origin + '/vendor/images/' + data.image);
                    }
                },
                error: function (data) {
                    console.log('Error:', data);
                    $.each(data.responseJSON.errors, function(key, value) {
                        toastr.error(value);
                    });
                    printErrorMsg(data.responseJSON.errors);
                    $('#update-profile').html('Submit');
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

        //Date picker
        $('#datepicker').datepicker({
            autoclose: true
        })
    });
</script>
@stop