<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sku' => 'SEC-' . strtoupper($this->faker->unique()->bothify('??####')),
            'nom' => 'Producte Genèric',
            'descripcio' => 'Descripció del producte de seguretat.',
            'preu' => $this->faker->randomFloat(2, 50, 800),
            'img' => 'img/default.jpg',
            'estoc' => $this->faker->numberBetween(5, 50),
            'categoria_id' => Categoria::inRandomOrder()->first()?->id ?? Categoria::factory(),
        ];
    }

    public function camara()
    {
        return $this->state(function (array $attributes) {
            static $idx = 1;
            return [
                'nom' => $this->faker->randomElement([
                    'Càmera IP 4K Full HD amb Visió Nocturna',
                    'Kit de 4 Càmeres WiFi per a Exterior',
                    'Càmera PTZ 360° amb IA de Detecció',
                    'Càmera de Domòtica Intel·ligent amb Alexa',
                    'Càmera de Seguretat per a Interior amb Àudio Bidireccional',
                    'Càmera Tèrmica per a Vigilància Nocturna',
                    'Càmera Oculta en Sensor de Moviment',
                    'Càmera 360° Panoràmica'
                ]),
                'descripcio' => 'Càmera de vigilància d\'alta definició amb visió nocturna i detecció de moviment intel·ligent.',
                'img' => 'img/camaras/camara' . ($idx > 5 ? ($idx = 1) : $idx++) . '.jpg',
            ];
        });
    }

    public function cerradura()
    {
        return $this->state(function (array $attributes) {
            static $idx = 1;
            return [
                'nom' => $this->faker->randomElement([
                    'Cerradura Intel·ligent amb Reconèixement Facial',
                    'Cerradura Electrònica amb Teclat Numèric',
                    'Cerradura Biomètrica d\'Alta Seguretat',
                    'Cerradura WiFi Controlada per App',
                    'Kit de Tancament Automàtic'
                ]),
                'descripcio' => 'Sistema de tancament avançat amb múltiples mètodes d\'accés i control remot.',
                'img' => 'img/cerraduras/cerradura' . ($idx > 5 ? ($idx = 1) : $idx++) . '.jpg',
            ];
        });
    }

    public function sensor()
    {
        return $this->state(function (array $attributes) {
            static $idx = 1;
            return [
                'nom' => $this->faker->randomElement([
                    'Sensor de Porta/Finestra amb Alerta',
                    'Detector de Moviment PIR Infraroig',
                    'Sensor de Trencament de Vidre',
                    'Sensor d\'Inundació WiFi',
                    'Detector de Fum i Calor connectat'
                ]),
                'descripcio' => 'Sensor de detecció precisa amb connexió immediata a la central d\'alarmes.',
                'img' => 'img/sensores/sensor' . ($idx > 5 ? ($idx = 1) : $idx++) . '.jpg',
            ];
        });
    }

    public function alarma()
    {
        return $this->state(function (array $attributes) {
            static $idx = 1;
            return [
                'nom' => $this->faker->randomElement([
                    'Kit d\'Alarma Sense Fils WiFi/GSM',
                    'Sirena de Gran Potència amb Llum Estroboscòpica',
                    'Panell de Control Tàctil per a Alarmes',
                    'Sistema d\'Alarma Híbrid Professional',
                    'Teclat d\'Accés per a Sistemes de Seguretat'
                ]),
                'descripcio' => 'Sistema de seguretat integral amb avisos immediats al telèfon mòbil.',
                'img' => 'img/alarmas/alarma' . ($idx > 5 ? ($idx = 1) : $idx++) . '.jpg',
            ];
        });
    }

    public function servicio()
    {
        return $this->state(function (array $attributes) {
            static $idx = 1;
            return [
                'nom' => $this->faker->randomElement([
                    'Servei d\'Instal·lació Professional',
                    'Manteniment Preventiu Anual',
                    'Monitoratge 24/7 de Grado 3',
                    'Auditoria de Seguretat Llar/Empresa',
                    'Servei de Resposta Immediata Acudida'
                ]),
                'descripcio' => 'Serveis professionals realitzats per tècnics experts en seguretat.',
                'img' => 'img/servicios/servicio' . ($idx > 5 ? ($idx = 1) : $idx++) . '.jpg',
                'estoc' => 99,
            ];
        });
    }
}

