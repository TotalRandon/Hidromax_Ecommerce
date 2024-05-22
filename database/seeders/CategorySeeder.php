<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            ['name' => 'Ferramentas e Equipamentos', 'slug' => 'ferramentas-e-equipamentos'],
            ['name' => 'Materiais de Construção', 'slug' => 'materiais-de-construcao'],
            ['name' => 'Elétrica', 'slug' => 'eletrica'],
            ['name' => 'Hidráulica', 'slug' => 'hidraulica'],
            ['name' => 'Pintura e Acabamento', 'slug' => 'pintura-e-acabamento'],
            ['name' => 'Revestimentos e Pisos', 'slug' => 'revestimentos-e-pisos'],
            ['name' => 'Portas e Janelas', 'slug' => 'portas-e-janelas'],
            ['name' => 'Jardinagem e Paisagismo', 'slug' => 'jardinagem-e-paisagismo'],
            ['name' => 'Decoração e Organização', 'slug' => 'decoracao-e-organizacao'],
            ['name' => 'Casa e Conforto', 'slug' => 'casa-e-conforto'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'status' => 1,
                'showHome' => 'Yes',
            ]);
        }
    }
}
