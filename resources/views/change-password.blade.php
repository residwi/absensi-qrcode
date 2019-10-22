@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Ubah Password - ')

@section('content_header')
<h1>Ubah Password</h1>

{{ Breadcrumbs::render('password') }}
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body">
                <form role="form" id="form-update-password">
                    <div class="box-body">
                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul class="list-unstyled"></ul>
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('current_password') ? 'has-error' : '' }}">
                            <label for="current_password">Password Lama</label>
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                placeholder="Current Password">
                            @if ($errors->has('current_password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('current_password') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                            <label for="password">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="New Password">
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
                        <button type="button" id="update-password" class="btn btn-primary">Submit</button>
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

        $('#update-password').click(function(e){
            e.preventDefault();
            $('#update-password').html('Sending..');
    
            $.ajax({
              data: $('#form-update-password').serialize(),
              url: "{{ route('password.users.update') }}",
              type: "PUT",
              dataType: 'json',
              success: function (data) {
                  $('#update-password').html('Submit');
                  $('#current_password').val("");
                  $('#password').val("");
                  $('#password_confirmation').val("");
                  toastr.success(data.message);
              },
              error: function (data) {
                console.log('Error:', data);
                $.each(data.responseJSON.errors, function(key, value) {
                    toastr.error(value);
                });
                $('#current_password').val("");
                $('#password').val("");
                $('#password_confirmation').val("");
                $('#update-password').html('Submit');
              }
            });
        });
    });
</script>
@stop