<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MahasiswaRequest extends FormRequest
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
            'nomor_induk' =>  'required|unique:users,username',
            'nama' => 'required',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required',
            'email' =>  'required|email|unique:mahasiswa,email',
            'password' => 'required|min:6|confirmed',
            'photo' => 'nullable|image|max:2048',
        ];

        if ($this->route('mahasiswa')) {
            $user = User::with('authable')->where('username', $this->route('mahasiswa'))->first();
            $rules['nomor_induk'] = [
                'required',
                Rule::unique('users', 'username')->ignoreModel($user),
            ];
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('mahasiswa', 'email')->ignoreModel($user->authable),
            ];
            $rules['password'] = 'nullable|min:6|confirmed';
        }

        return $rules;
    }
}
