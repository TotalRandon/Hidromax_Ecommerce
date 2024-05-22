<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class BrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $brands = [
            ['name' => 'Votorantim Cimentos', 'slug' => 'votorantim-cimentos'],
            ['name' => 'Tigre', 'slug' => 'tigre'],
            ['name' => 'Gerdau', 'slug' => 'gerdau'],
            ['name' => 'Vedacit', 'slug' => 'vedacit'],
            ['name' => 'Deca', 'slug' => 'deca'],
            ['name' => 'Coral', 'slug' => 'coral'],
            ['name' => 'Suvinil', 'slug' => 'suvinil'],
            ['name' => 'Quartzolit', 'slug' => 'quartzolit'],
            ['name' => 'Pamesa', 'slug' => 'pamesa'],
            ['name' => 'Fortlev', 'slug' => 'fortlev'],
            ['name' => 'Portobello', 'slug' => 'portobello'],
            ['name' => 'Eliane', 'slug' => 'eliane'],
            ['name' => 'Amanco', 'slug' => 'amanco'],
            ['name' => 'Hydra', 'slug' => 'hydra'],
            ['name' => 'Docol', 'slug' => 'docol'],
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'status' => 1,
            ]);
        }
    }
}
