<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = App\Admin::create([
            'nama' => 'admin',
            'tanggal_lahir' => date('Y-m-d'),
            'alamat' => 'tangerang',
            'email' => 'admin@mail.com',
            'no_telp' => '087885138545',
            'jenis_kelamin' => 1,
            'photo' => 'default-user.png',
        ]);

        $admin->authInfo()->create([
            'username' => '1001',
            'password' => Hash::make('admin'),
            'role_id' => 1,
        ]);

        
    }
}
