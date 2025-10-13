<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Topic;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            [
                'name' => 'Introducción al Manejo Integrado de Plagas',
                'description' => 'Conceptos básicos, importancia y principios del manejo integrado de plagas (MIP) en la agricultura.',
                'code' => 'MIPINTRO',
                'is_approved' => true,
            ],
            [
                'name' => 'Identificación de Plagas Agrícolas',
                'description' => 'Métodos y herramientas para la identificación de plagas en cultivos agrícolas.',
                'code' => 'IDPLAGA',
                'is_approved' => true,
            ],
            [
                'name' => 'Ciclo Biológico de Plagas',
                'description' => 'Estudio de los ciclos de vida de las principales plagas agrícolas y su relevancia para el control.',
                'code' => 'CICLOPLG',
                'is_approved' => true,
            ],
            [
                'name' => 'Monitoreo y Muestreo de Plagas',
                'description' => 'Técnicas de monitoreo, muestreo y registro de poblaciones de plagas en campo.',
                'code' => 'MONITPLG',
                'is_approved' => true,
            ],
            [
                'name' => 'Control Biológico de Plagas',
                'description' => 'Uso de enemigos naturales y organismos benéficos para el control de plagas.',
                'code' => 'CTRLBIOL',
                'is_approved' => true,
            ],
            [
                'name' => 'Control Químico y Uso Responsable de Plaguicidas',
                'description' => 'Principios para el uso seguro y responsable de plaguicidas en el manejo integrado.',
                'code' => 'CTRLQMC',
                'is_approved' => false,
            ],
            [
                'name' => 'Control Cultural y Prácticas Agronómicas',
                'description' => 'Prácticas culturales y agronómicas para prevenir y reducir la incidencia de plagas.',
                'code' => 'CTRLCLTR',
                'is_approved' => false,
            ],
            [
                'name' => 'Resistencia a Plaguicidas',
                'description' => 'Causas, consecuencias y manejo de la resistencia de plagas a plaguicidas.',
                'code' => 'RESPLAG',
                'is_approved' => false,
            ],
            [
                'name' => 'Normatividad y Buenas Prácticas en MIP',
                'description' => 'Regulaciones, normativas y buenas prácticas agrícolas relacionadas con el MIP.',
                'code' => 'NORMMIP',
                'is_approved' => true,
            ],
            [
                'name' => 'Casos Prácticos de Manejo Integrado de Plagas',
                'description' => 'Estudios de caso y experiencias exitosas en la implementación del MIP.',
                'code' => 'CASOMIP',
                'is_approved' => true,
            ],
        ];

        foreach ($topics as $topicData) {
            Topic::create($topicData);
        }
    }
}
