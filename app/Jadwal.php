<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $guarded = [];
    protected $table = 'jadwal';
    public $timestamps = false;

    public function matkul()
    {
        return $this->belongsTo('App\Matkul');
    }

    public function schedulable()
    {
        return $this->morphTo();
    }

    public function jurnals()
    {
        return $this->hasMany('App\Jurnal');
    }

    public function scopeDosen()
    {
        return static::with('schedulable')->where('schedulable_type', 'App\Dosen');
    }

    public function scopeMahasiswa()
    {
        return static::with('schedulable')->where('schedulable_type', 'App\Mahasiswa');
    }

    public function getHariAttribute($value)
    {
       $hari = [
           'Senin',
           'Selasa',
           'Rabu',
           'Kamis',
           'Jum\'at',
           'Sabtu',
           'Minggu',
       ];

       return $hari[$value - 1];
    }

    public function setHariAttribute($value)
    {
       $hari = [
           'Senin',
           'Selasa',
           'Rabu',
           'Kamis',
           'Jum\'at',
           'Sabtu',
           'Minggu',
       ];

       $this->attributes['hari'] = array_search($value, $hari) + 1;
    }


    public function setJamMulaiAttribute($value)
    {
        $this->attributes['jam_mulai'] = date("H:i", strtotime($value));
    }

    public function getJamMulaiAttribute($value)
    {
        return date("H:i", strtotime($value));
    }

    public function setJamSelesaiAttribute($value)
    {
        $this->attributes['jam_selesai'] = date("H:i", strtotime($value));
    }

    public function getJamSelesaiAttribute($value)
    {
        return date("H:i", strtotime($value));
    }
}
