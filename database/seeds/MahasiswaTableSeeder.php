<?php

use Illuminate\Database\Seeder;

class MahasiswaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Mahasiswa::class, 10)->create()->each(function ($mahasiswa) {
            $mahasiswa->authInfo()->save(factory(App\User::class)->make());
        });
    }
}
