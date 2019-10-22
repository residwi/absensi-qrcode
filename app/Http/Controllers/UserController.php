<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function profile()
    {
        $user = User::with('authable')->whereId(auth()->user()->id)->firstOrFail();
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::with('authable')->whereId(auth()->user()->id)->first();

        $validated = $request->validate([
            'nama' => 'required',
            'alamat' => 'nullable|string',
            'no_telp' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'required',
            'email' => 'required|email|unique:' . auth()->user()->getRole() . ',email,' . $user->authable->id,
            'photo' => 'nullable|image|max:2048',
        ]);

        $user->authable->nama = $validated['nama'];
        $user->authable->tanggal_lahir = $validated['tanggal_lahir'];
        $user->authable->alamat = $validated['alamat'];
        $user->authable->email = $validated['email'];
        $user->authable->no_telp = $validated['no_telp'];
        $user->authable->jenis_kelamin = $validated['jenis_kelamin'];

        if (isset($validated['photo'])) {
            if (($user->authable->photo != 'default-user.png')) {
                unlink(public_path('vendor/images/' . $user->authable->photo));
            }

            $imageName = $user->username . '-' . time() . '.' . $validated['photo']->extension();
            $user->authable->photo = $imageName;
            $validated['photo']->move(public_path('vendor/images'), $imageName);

            $response['image'] = $imageName;
        }

        $user->authable->save();
        $response['message'] = 'Profile berhasil di update';
        
        return response()->json($response);
    }

    public function changePassword()
    {
        return view('change-password');
    }

    public function updatePassword(Request $request)
    {
        $user = User::with('authable')->whereId(auth()->user()->id)->first();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        if (\Hash::check($validated['current_password'], $user->password)) {
            $user->password = \Hash::make($validated['password']);
            $user->save();

            return response()->json(['message' => 'Password berhasil di ganti']);
        }

        return response()->json(['message' => 'Password lama anda salah'], 422);
    }
}
