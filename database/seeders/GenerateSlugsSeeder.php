<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plant;
use Illuminate\Support\Str;

class GenerateSlugsSeeder extends Seeder
{
    public function run()
    {
        $plants = Plant::all();
        
        foreach ($plants as $plant) {
            if (empty($plant->slug)) {
                $plant->slug = Str::slug($plant->name);
                $plant->save();
                $this->command->info("Generated slug for: {$plant->name} -> {$plant->slug}");
            }
        }
        
        $this->command->info("✅ All plants now have slugs!");
    }
}
