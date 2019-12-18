@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Buat Jurnal - ')

@section('content_header')
<h1>Buat Jurnal</h1>

{{ Breadcrumbs::render('jurnal', $jadwal, $jurnal) }}
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <!-- /.box-header -->
            <div class="box-body">
                <form role="form" method="POST"
                    action="{{ route('jadwal.jurnal.store', ['id' => $jurnal->jadwal_id, 'pertemuan' => $jurnal->pertemuan]) }}">
                    @csrf
                    <div class="box-body">
                        <div class="form-group has-feedback {{ $errors->has('materi') ? 'has-error' : '' }}">
                            <label for="materi">Materi</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control" id="materi" name="materi"
                                placeholder="Materi Kuliah" value="{{ old('materi') }}" required>
                            @if ($errors->has('materi'))
                            <span class="help-block">
                                <strong>{{ $errors->first('materi') }}</strong>
                            </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('keterangan') ? 'has-error' : '' }}">
                            <label for="keterangan">Keterangan</label><span class="text-danger"> *</span>
                            <input type="text" class="form-control" id="keterangan" name="keterangan"
                                placeholder="Keterangan" value="{{ old('keterangan') }}" required>
                            @if ($errors->has('keterangan'))
                            <span class="help-block">
                                <strong>{{ $errors->first('keterangan') }}</strong>
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

