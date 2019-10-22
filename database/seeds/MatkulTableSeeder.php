<?php

use Illuminate\Database\Seeder;

class MatkulTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $matkul = factory(App\Matkul::class, 10)->create();
    }
}
