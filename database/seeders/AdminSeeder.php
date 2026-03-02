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
        $email = env('ADMIN_EMAIL', 'admin@seherbal.com');
        $password = env('ADMIN_PASSWORD', 'admin123');

        // Cek apakah admin sudah ada
        $adminExists = User::where('email', $email)->first();
        
        if ($adminExists) {
            $this->command->info('Admin sudah ada di database!');
            return;
        }

        // Buat akun admin baru
        User::create([
            'name' => 'Admin SeHerbal',
            'email' => $email,
            'password' => Hash::make($password), // Password: admin123
        ]);

        $this->command->info('✅ Admin berhasil dibuat!');
    }
}
