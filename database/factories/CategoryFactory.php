<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Tintas', 'Ferramentas', 'Porcelanatos e Pisos', 'Banheiro', 'Hidraulica', 'Iluminação', 'Ventilação', 'Portas e Janelas', 'Utilidade Doméstica', 'Cozinha e Lavanderia', 'Jardim', 'Elétrica', 'Material Básico', ];
        $category = $categories[array_rand($categories)];  // Seleção aleatória

        return [
            'name' => $category,
            'status' => rand(0, 1),
            'slug' => Str::slug($category)  // Criando um slug apropriado
        ];
    }
}
