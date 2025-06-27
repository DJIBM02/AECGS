<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Fête de la Culture Camerounaise 2025',
                'subtitle' => 'Célébration de notre patrimoine culturel',
                'description' => "Rejoignez-nous pour notre événement annuel phare ! Une journée entière dédiée à la célébration de la richesse culturelle camerounaise. Au programme : danses traditionnelles, musique live, exposition d'art, dégustation de plats typiques, ateliers pour enfants et bien plus encore. C'est l'occasion parfaite de découvrir ou redécouvrir nos traditions dans une atmosphère conviviale et familiale.",
                'image' => 'img/476589909_122216537072196747_1503253726995860021_n.jpg',
                'event_date' => Carbon::now()->addMonths(2)->format('Y-m-d'),
                'event_time' => '10:00',
                'location' => 'Centre Communautaire Tom Davies Square, Sudbury',
                'status' => 'upcoming',
                'type' => 'cultural',
                'max_participants' => 300,
                'current_participants' => 45,
                'is_featured' => true,
                'price' => 15.00,
                'requirements' => 'Aucun prérequis. Événement familial ouvert à tous.',
                'contact_info' => [
                    'email' => 'culture@aecgs.ca',
                    'phone' => '+1 705 XXX XXXX'
                ]
            ],
            [
                'title' => 'Fête Nationale du Cameroun - 20 Mai',
                'subtitle' => 'Célébration de l\'unité nationale',
                'description' => "Célébrons ensemble la fête nationale du Cameroun ! Cette journée spéciale sera marquée par une cérémonie officielle, des discours inspirants, la présentation des couleurs nationales, et des activités culturelles. Un moment de fierté et d'unité pour tous les Camerounais du Grand Sudbury.",
                'image' => 'img/472622654_122204637824206950_8946875114983751356_n.jpg',
                'event_date' => Carbon::create(2025, 5, 20)->format('Y-m-d'),
                'event_time' => '14:00',
                'location' => 'Parc Bell, Sudbury',
                'status' => 'upcoming',
                'type' => 'cultural',
                'max_participants' => 200,
                'current_participants' => 23,
                'is_featured' => true,
                'price' => 0.00,
                'requirements' => 'Apportez vos chaises pliantes. Événement en extérieur.',
                'contact_info' => [
                    'email' => 'events@aecgs.ca',
                    'phone' => '+1 705 XXX XXXX'
                ]
            ],
            [
                'title' => 'Soirée de Gala AECGS 2024',
                'subtitle' => 'Une soirée mémorable de célébration',
                'description' => "Retour sur notre magnifique soirée de gala annuelle qui a réuni plus de 200 membres de la communauté camerounaise. Une soirée élégante avec dîner, discours, remise de prix, et spectacles. Merci à tous les participants qui ont fait de cet événement un grand succès !",
                'image' => 'img/425786765_122111963090196747_8439411231553292887_n.png',
                'event_date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
                'event_time' => '18:00',
                'location' => 'Hôtel Holiday Inn Express & Suites, Sudbury',
                'status' => 'completed',
                'type' => 'social',
                'max_participants' => 250,
                'current_participants' => 220,
                'is_featured' => false,
                'price' => 75.00,
                'requirements' => 'Tenue de soirée exigée.',
                'contact_info' => [
                    'email' => 'gala@aecgs.ca',
                    'phone' => '+1 705 XXX XXXX'
                ]
            ],
            [
                'title' => 'Atelier d\'Intégration pour Nouveaux Arrivants',
                'subtitle' => 'Faciliter votre installation au Canada',
                'description' => "Un atelier pratique destiné aux nouveaux arrivants camerounais. Nous couvrirons les sujets essentiels : démarches administratives, système de santé, recherche d'emploi, logement, éducation, et services bancaires. Session interactive avec des experts et témoignages de membres établis.",
                'image' => 'img/default-event.jpg',
                'event_date' => Carbon::now()->addWeeks(3)->format('Y-m-d'),
                'event_time' => '13:00',
                'location' => 'Bibliothèque publique du Grand Sudbury - Succursale principale',
                'status' => 'upcoming',
                'type' => 'educational',
                'max_participants' => 50,
                'current_participants' => 18,
                'is_featured' => false,
                'price' => 0.00,
                'requirements' => 'Apportez vos documents d\'immigration et un carnet de notes.',
                'contact_info' => [
                    'email' => 'integration@aecgs.ca',
                    'phone' => '+1 705 XXX XXXX'
                ]
            ],
            [
                'title' => 'Tournoi de Football AECGS',
                'subtitle' => 'Compétition sportive annuelle',
                'description' => "Notre tournoi de football annuel est de retour ! Ouvert à tous les membres de la communauté, ce tournoi amical favorise l'esprit sportif et le renforcement des liens. Plusieurs catégories : seniors, juniors, et mixte. Prix et trophées pour les gagnants. Buvette et restauration sur place.",
                'image' => 'img/default-event.jpg',
                'event_date' => Carbon::now()->addMonths(4)->format('Y-m-d'),
                'event_time' => '09:00',
                'location' => 'Terrain de sport James Jerome Sports Complex',
                'status' => 'upcoming',
                'type' => 'sports',
                'max_participants' => 100,
                'current_participants' => 12,
                'is_featured' => false,
                'price' => 10.00,
                'requirements' => 'Équipement sportif obligatoire. Assurance responsabilité civile recommandée.',
                'contact_info' => [
                    'email' => 'sports@aecgs.ca',
                    'phone' => '+1 705 XXX XXXX'
                ]
            ],
            [
                'title' => 'Conférence : Entrepreneuriat en Ontario',
                'subtitle' => 'Développer son business au Canada',
                'description' => "Une conférence dédiée à l'entrepreneuriat destinée aux membres de la communauté qui souhaitent créer ou développer leur entreprise en Ontario. Intervenants experts, témoignages d'entrepreneurs à succès, présentation des programmes d'aide gouvernementaux, et session de networking.",
                'image' => 'img/default-event.jpg',
                'event_date' => Carbon::now()->addWeeks(6)->format('Y-m-d'),
                'event_time' => '10:00',
                'location' => 'Collège Boréal - Campus de Sudbury',
                'status' => 'upcoming',
                'type' => 'educational',
                'max_participants' => 80,
                'current_participants' => 31,
                'is_featured' => true,
                'price' => 25.00,
                'requirements' => 'Apportez votre projet d\'entreprise si vous en avez un.',
                'contact_info' => [
                    'email' => 'business@aecgs.ca',
                    'phone' => '+1 705 XXX XXXX'
                ]
            ],
            [
                'title' => 'Pique-nique Familial d\'Été',
                'subtitle' => 'Retrouvailles en famille',
                'description' => "Un moment de détente et de convivialité pour toute la famille ! Pique-nique géant avec jeux pour enfants, activités sportives, concours de talents, et bien sûr de délicieux plats à partager. Chaque famille apporte un plat à partager. Activités adaptées à tous les âges.",
                'image' => 'img/default-event.jpg',
                'event_date' => Carbon::create(2025, 7, 15)->format('Y-m-d'),
                'event_time' => '11:00',
                'location' => 'Parc Kivi, Sudbury',
                'status' => 'upcoming',
                'type' => 'community',
                'max_participants' => null, // Illimité
                'current_participants' => 67,
                'is_featured' => false,
                'price' => 0.00,
                'requirements' => 'Chaque famille apporte un plat à partager et ses boissons.',
                'contact_info' => [
                    'email' => 'famille@aecgs.ca',
                    'phone' => '+1 705 XXX XXXX'
                ]
            ]
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }

        $this->command->info('Événements créés avec succès !');
    }
}