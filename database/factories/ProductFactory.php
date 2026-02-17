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
        // Llista de noms realistes de productes de seguretat
        $cameraNames = [
            'Càmera IP 4K Full HD amb Visió Nocturna',
            'Kit de 4 Càmeres WiFi per a Exterior',
            'Càmera PTZ 360° amb IA de Detecció',
            'Càmera de Domòtica Intel·ligent amb Alexa',
            'Càmera de Seguretat per a Interior amb Àudio Bidireccional',
            'Càmera Tèrmica per a Vigilància Nocturna',
            'Càmera Oculta en Sensor de Moviment',
            'Càmera de Placa per a Portes Intel·ligents',
            'Càmera 360° Panoràmica per a Negocis',
            'Càmera Subaquàtica per a Piscines',
            'Gravadora NVR 8 Canals amb 2TB',
            'Sistema de Videovigilància Professional 16 Canals',
            'Disc Dur 4TB per a Vigilància',
            'Kit de Sensors de Moviment Externs',
            'Alarma Intel·ligent amb Notificacions Push',
            'Cerradura Intel·ligent amb Reconèixement Facial',
            'Sensor de Porta/Finestra amb Alerta',
            'Detector de Fum i Monòxid connectat',
            'Sirena d\'Alarma amb Bateria de Reserva',
            'Software de Gestió de Vigilància Professional',
            'Servei d\'Instal·lació Professional',
            'Servei de Manteniment Mensual',
            'Servei de Monitoratge 24/7',
            'Kit de Càmeres per a Cotxe',
            'Càmera de Taula per a Oficines',
        ];

        // Descripcions específiques per a productes de seguretat
        $descriptions = [
            'Càmera de vigilància amb resolució 4K, visió nocturna fins a 30m i detecció de moviment amb alertes en temps real.',
            'Sistema complet de vigilància amb 4 càmeres WiFi, gravadora NVR i disc dur de 2TB per a gravacions contínues.',
            'Càmera amb moviment remot controlable des de l\'aplicació mòbil i visió panoràmica de 360 graus.',
            'Càmera amb Intel·ligència Artificial per reconèixer persones, vehicles i animals, reduint falses alarmes.',
            'Solució professional per a negocis amb 16 entrades, suport 4K i accés remot des de qualsevol dispositiu.',
        ];

        return [
            'sku' => 'SEC-' . strtoupper($this->faker->unique()->bothify('??####')),
            'nom' => $this->faker->randomElement($cameraNames),
            'descripcio' => $this->faker->randomElement($descriptions),
            'preu' => $this->faker->randomFloat(2, 50, 800),
            'img' => 'img/camaras/camera-' . $this->faker->randomElement(['ip', 'ptz', 'nvr', 'domotica', 'exterior', 'interior']) . '-' . $this->faker->numberBetween(1, 10) . '.jpg',
            'estoc' => $this->faker->numberBetween(5, 50),
            'categoria_id' => Categoria::factory(),
        ];
    }
}