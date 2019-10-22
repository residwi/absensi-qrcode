<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DosenRequest extends FormRequest
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
        return [
            'nomor_induk' => 'required|unique:users,username',
            'nama' => 'required',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required',
            'email' => 'required|email|unique:dosen',
            'password' => 'required|min:6|confirmed',
            'photo' => 'nullable|image|max:2048',
        ];
    }
}
