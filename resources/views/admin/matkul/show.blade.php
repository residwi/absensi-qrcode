@extends('adminlte::page')

@section('title', 'Siakad UPWI')
@section('title_prefix', 'Detail Matkul - ')

@section('content_header')
<h1>Detail Mata Kuliah</h1>

{{ Breadcrumbs::render('matkul.show', $matkul) }}
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                {{-- <h3 class="box-title">Tambah Mata Kuliah</h3> --}}
                <a class="btn btn-warning" href="{{ route('matkul.edit', $matkul->id) }}">
                    <span class="fas fa-fw fa-edit"></span> Edit Mata Kuliah
                </a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form role="form">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="kode">Kode Mata Kuliah</label>
                            <input type="text" class="form-control" value="{{ $matkul->kode }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama Mata Kuliah</label>
                            <input type="text" class="form-control" value="{{ $matkul->nama }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="ruang">Ruangan</label>
                            <input type="text" class="form-control" value="{{ $matkul->ruang }}" readonly>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </form>
            </div>
        </div>
    </div>
</div>
@stop

