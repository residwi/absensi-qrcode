<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $guarded = [];
    protected $table = 'jurnal';

    public function jadwal()
    {
        return $this->belongsTo('App\Jadwal'); 
    }

    public function absensi()
    {
        return $this->hasMany('App\Absensi');
    }
}
