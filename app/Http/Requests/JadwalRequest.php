<?php

namespace App\Http\Requests;

use App\Jadwal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JadwalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'matkul' => [
                'required',
                Rule::unique('jadwal', 'matkul_id')->where(function ($query) {
                    return $query->where('schedulable_type', 'App\Dosen');
                }),
            ],
            'dosen' => 'required',
            'hari' => 'required',
            'jam_mulai' => [
                'required',
                'date_format:H:i',
                Rule::unique('jadwal', 'jam_mulai')->where(function ($query) {
                    return $query->where(['schedulable_type' => 'App\Dosen', 'hari' => $this->hari]);
                }),
            ],
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ];

        if ($this->route('jadwal')) {
            $jadwal = Jadwal::find($this->route('jadwal'));
            $rules['matkul'] = [
                'required',
                Rule::unique('jadwal', 'matkul_id')->where(function ($query) {
                    return $query->where('schedulable_type', 'App\Dosen');
                })->ignoreModel($jadwal),
            ];
            $rules['jam_mulai'] = [
                'required',
                'date_format:H:i',
                Rule::unique('jadwal', 'jam_mulai')->where(function ($query) {
                    return $query->where(['schedulable_type' => 'App\Dosen', 'hari' => $this->hari]);
                })->ignoreModel($jadwal),
            ];
        }

        return $rules;
    }
}
