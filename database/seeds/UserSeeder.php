<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User;
        $user->name = "Administrator";
        $user->email = "admin@mail.com";
        $user->password = \Hash::make("admin123");
        $user->status = true;
        $user->save();

        $this->command->info("Seeder User Berhasil");
    }
}
