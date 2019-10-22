<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleTableSeeder::class, 
            AdminTableSeeder::class,
            DosenTableSeeder::class,
            MahasiswaTableSeeder::class,
            MatkulTableSeeder::class,
        ]);
    }
}
