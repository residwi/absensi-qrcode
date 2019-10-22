<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $guarded = [];
    protected $table = 'mahasiswa';

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
