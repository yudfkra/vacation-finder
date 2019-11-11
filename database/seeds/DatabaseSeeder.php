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
        // $this->call(UsersTableSeeder::class);

        factory(App\User::class)->create([
            'name' => 'Administrator',
            'email' => 'admin@mail.com',
        ]);

        // factory(App\Tour::class, 18)->create();
    }
}
