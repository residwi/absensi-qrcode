@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Tambah Jadwal - ')

@section('content_header')
<h1>Tambah Jadwal</h1>

{{ Breadcrumbs::render('jadwal.create') }}
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <form role="form" method="POST" action="{{ route('jadwal.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="box-body">
                        <div class="form-group has-feedback {{ $errors->has('matkul') ? 'has-error' : '' }}">
                            <label for="matkul">Mata Kuliah</label><span class="text-danger"> *</span>
                            <select id="matkul" class="form-control" name="matkul" required data-placeholder="Pilih Matkul">
                            </select>
                            @if ($errors->has('matkul'))
                            <span class="help-block">
                                <strong>{{ $errors->first('matkul') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('dosen') ? 'has-error' : '' }}">
                            <label for="dosen">Dosen Pengajar</label><span class="text-danger"> *</span>
                            <select id="dosen" class="form-control" name="dosen" required data-placeholder="Pilih Dosen">
                            </select>
                            @if ($errors->has('dosen'))
                            <span class="help-block">
                                <strong>{{ $errors->first('dosen') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('hari') ? 'has-error' : '' }}">
                            <label for="hari">Hari</label><span class="text-danger"> *</span>
                            <select id="hari" class="form-control" name="hari" required data-placeholder="Pilih Hari">
                                <option></option>
                                <option value="1">Senin</option>
                                <option value="2">Selasa</option>
                                <option value="3">Rabu</option>
                                <option value="4">Kamis</option>
                                <option value="5">Jum'at</option>
                                <option value="6">Sabtu</option>
                                <option value="7">Minggu</option>
                            </select>
                            @if ($errors->has('hari'))
                            <span class="help-block">
                                <strong>{{ $errors->first('hari') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('jam_mulai') ? 'has-error' : '' }}">
                            <label for="jam_mulai">Jam Mulai</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control timepicker" id="jam_mulai" name="jam_mulai"
                                placeholder="Jam Mulai" required>
                            @if ($errors->has('jam_mulai'))
                            <span class="help-block">
                                <strong>{{ $errors->first('jam_mulai') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('jam_selesai') ? 'has-error' : '' }}">
                            <label for="jam_selesai">Jam Selesai</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control timepicker" id="jam_selesai" name="jam_selesai"
                                placeholder="Jam Selesai" required>
                            @if ($errors->has('jam_selesai'))
                            <span class="help-block">
                                <strong>{{ $errors->first('jam_selesai') }}</strong>
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
    $(document).ready(function () {
        $("#matkul").select2({
        ajax: {
            url: '{{ route("admin.ajaxsearch.matkul") }}',
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
                            text: obj.kode + ' - ' + obj.nama
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

    $("#dosen").select2({
        ajax: {
            url: '{{ route("admin.ajaxsearch.dosen") }}',
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

    $("#hari").select2({
        placeholder: function(){            
            $(this).data('placeholder');
        },
        minimumInputLength: 1,
        allowClear: true,
        cache: true
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

    $('.timepicker').timepicker({
      showInputs: false,
      showMeridian: false,
      explicitMode: true,
      defaultTime: false
    });
});
</script>
@stop