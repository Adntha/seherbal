<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $adminExists = User::where('email', 'admin@seherbal.com')->first();
        
        if ($adminExists) {
            $this->command->info('Admin sudah ada di database!');
            return;
        }

        // Buat akun admin baru
        User::create([
            'name' => 'Admin SeHerbal',
            'email' => 'admin@seherbal.com',
            'password' => Hash::make('admin123'), // Password: admin123
        ]);

        $this->command->info('✅ Admin berhasil dibuat!');
        $this->command->info('Email: admin@seherbal.com');
        $this->command->info('Password: admin123');
    }
}
