<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $subcategories = [
            // Ferramentas e Equipamentos
            ['name' => 'Ferramentas Manuais', 'slug' => 'ferramentas-manuais', 'category_id' => 2],
            ['name' => 'Ferramentas Elétricas', 'slug' => 'ferramentas-eletricas', 'category_id' => 2],
            ['name' => 'Equipamentos de Segurança', 'slug' => 'equipamentos-de-seguranca', 'category_id' => 2],
            ['name' => 'Fixadores e Acessórios', 'slug' => 'fixadores-e-acessorios', 'category_id' => 2],
            
            // Materiais de Construção
            ['name' => 'Cimento e Argamassa', 'slug' => 'cimento-e-argamassa', 'category_id' => 3],
            ['name' => 'Tijolos e Blocos', 'slug' => 'tijolos-e-blocos', 'category_id' => 3],
            ['name' => 'Madeira e Derivados', 'slug' => 'madeira-e-derivados', 'category_id' => 3],
            ['name' => 'Aço e Ferro', 'slug' => 'aco-e-ferro', 'category_id' => 3],
            
            // Elétrica
            ['name' => 'Fios e Cabos', 'slug' => 'fios-e-cabos', 'category_id' =>4],
            ['name' => 'Disjuntores e Quadros de Distribuição', 'slug' => 'disjuntores-e-quadros-de-distribuicao', 'category_id' =>4],
            ['name' => 'Tomadas e Interruptores', 'slug' => 'tomadas-e-interruptores', 'category_id' =>4],
            ['name' => 'Iluminação', 'slug' => 'iluminacao', 'category_id' =>4],
            
            // Hidráulica
            ['name' => 'Tubos e Conexões', 'slug' => 'tubos-e-conexoes', 'category_id' => 5],
            ['name' => 'Torneiras e Registros', 'slug' => 'torneiras-e-registros', 'category_id' => 5],
            ['name' => 'Caixas d\'Água e Cisternas', 'slug' => 'caixas-dagua-e-cisternas', 'category_id' => 5],
            ['name' => 'Bombas e Filtros', 'slug' => 'bombas-e-filtros', 'category_id' => 5],
            
            // Pintura e Acabamento
            ['name' => 'Tintas e Vernizes', 'slug' => 'tintas-e-vernizes', 'category_id' => 6],
            ['name' => 'Pincéis e Rolos', 'slug' => 'pinceis-e-rolos', 'category_id' => 6],
            ['name' => 'Massa Corrida e Gesso', 'slug' => 'massa-corrida-e-gesso', 'category_id' => 6],
            ['name' => 'Papéis de Parede e Adesivos', 'slug' => 'papeis-de-parede-e-adesivos', 'category_id' => 6],
            
            // Revestimentos e Pisos
            ['name' => 'Cerâmicas e Porcelanatos', 'slug' => 'ceramicas-e-porcelanatos', 'category_id' => 7],
            ['name' => 'Laminados e Vinílicos', 'slug' => 'laminados-e-vinilicos', 'category_id' => 7],
            ['name' => 'Azulejos e Pastilhas', 'slug' => 'azulejos-e-pastilhas', 'category_id' => 7],
            ['name' => 'Tapetes e Carpetes', 'slug' => 'tapetes-e-carpetes', 'category_id' => 7],
            
            // Portas e Janelas
            ['name' => 'Portas Internas e Externas', 'slug' => 'portas-internas-e-externas', 'category_id' => 8],
            ['name' => 'Janelas de Alumínio e PVC', 'slug' => 'janelas-de-aluminio-e-pvc', 'category_id' => 8],
            ['name' => 'Fechaduras e Dobradiças', 'slug' => 'fechaduras-e-dobradicas', 'category_id' => 8],
            ['name' => 'Persiana e Cortinas', 'slug' => 'persiana-e-cortinas', 'category_id' => 8],
            
            // Jardinagem e Paisagismo
            ['name' => 'Ferramentas de Jardinagem', 'slug' => 'ferramentas-de-jardinagem', 'category_id' => 9],
            ['name' => 'Plantas e Sementes', 'slug' => 'plantas-e-sementes', 'category_id' => 9],
            ['name' => 'Vasos e Floreiras', 'slug' => 'vasos-e-floreiras', 'category_id' => 9],
            ['name' => 'Sistemas de Irrigação', 'slug' => 'sistemas-de-irrigacao', 'category_id' => 9],
            
            // Decoração e Organização
            ['name' => 'Móveis e Prateleiras', 'slug' => 'moveis-e-prateleiras', 'category_id' => 10],
            ['name' => 'Espelhos e Quadros', 'slug' => 'espelhos-e-quadros', 'category_id' => 10],
            ['name' => 'Caixas Organizadoras', 'slug' => 'caixas-organizadoras', 'category_id' => 10],
            ['name' => 'Cortinas e Tapetes', 'slug' => 'cortinas-e-tapetes', 'category_id' => 10],
            
            // Casa e Conforto
            ['name' => 'Produtos de Limpeza', 'slug' => 'produtos-de-limpeza', 'category_id' => 11],
            ['name' => 'Eletrodomésticos', 'slug' => 'eletrodomesticos', 'category_id' => 11],
            ['name' => 'Itens de Utilidade Doméstica', 'slug' => 'itens-de-utilidade-domestica', 'category_id' => 11],
            ['name' => 'Ar-Condicionado e Ventiladores', 'slug' => 'ar-condicionado-e-ventiladores', 'category_id' => 11],
        ];

        foreach ($subcategories as $subcategory) {
            DB::table('sub_categories')->insert([
                'name' => $subcategory['name'],
                'slug' => Str::slug($subcategory['name']),
                'status' => 1,
                'showHome' => 'Yes',
                'category_id' => $subcategory['category_id'],
            ]);
        }
    }
}
