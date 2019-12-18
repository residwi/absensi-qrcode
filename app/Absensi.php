<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $guarded = [];
    protected $table = 'absensi';
    public $timestamps = false;

    public function jurnal()
    {
        return $this->belongsTo('App\Jurnal');
    }

    public function mahasiswa()
    {
        return $this->belongsTo('App\Mahasiswa');
    }

    public function getStatusAttribute($value)
    {
        $status = [
            'Tidak Hadir',
            'Hadir',
            'Sakit',
            'Izin'
        ];

        return $status[$value - 1];
    }

    public function setStatusAttribute($value)
    {
        $status = [
            'Tidak Hadir',
            'Hadir',
            'Sakit',
            'Izin'
        ];

       $this->attributes['status'] = array_search($value, $status) + 1;
    }
}
