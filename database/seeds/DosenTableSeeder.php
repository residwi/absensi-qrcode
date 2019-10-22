<?php

use Illuminate\Database\Seeder;

class DosenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Dosen::class, 10)->create()->each(function ($dosen) {
            $dosen->authInfo()->save(factory(App\User::class)->make(['role_id' => 2]));
        });
    }
}
