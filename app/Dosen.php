<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $guarded = [];
    protected $table = 'dosen';

    public function authInfo()
    {
        return $this->morphOne('App\User', 'authable');
    }

    public function schedules()
    {
        return $this->morphMany('App\Jadwal', 'schedulable');
    }

    public function scopeGetAuthInfo()
    {
        return static::with('authInfo');
    }

    public function matkul()
    {
        return $this->belongsToMany('App\Matkul');
    }

    public function getJenisKelaminAttribute($value)
    {
        return $value == 1 ? 'Laki-Laki' : 'Perempuan';
    }

    public function setTanggalLahirAttribute($value)
    {
        $this->attributes['tanggal_lahir'] = date("Y-m-d", strtotime($value));
    }

    public function getTanggalLahirAttribute($value)
    {
        return date('m/d/Y', strtotime($value));
    }
}
