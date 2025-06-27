<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Si besoin de données à passer à la vue
        $currentDate = now()->format('d F Y');

        // Événements pour la page d'accueil
        $pastEvents = collect([
            [
                'id' => 1,
                'title' => 'Fête de la Culture Camerounaise',
                'subtitle' => 'Retour sur la Fête de la Culture Camerounaise',
                'description' => "L'événement annuel de l'AECGS a célébré la richesse culturelle camerounaise avec des danses, de la musique et des spécialités culinaires. Un grand moment de partage et de convivialité pour tous les membres de la communauté.",
                'image' => 'img/476589909_122216537072196747_1503253726995860021_n.jpg',
                'date' => '15 Décembre 2024',
                'location' => 'Centre Communautaire de Sudbury'
            ],
            [
                'id' => 2,
                'title' => 'Soirée de Gala AECGS',
                'subtitle' => 'Une soirée mémorable',
                'description' => "Notre gala annuel a réuni plus de 200 membres de la communauté camerounaise pour une soirée de réseautage et de célébration.",
                'image' => 'img/472622654_122204637824206950_8946875114983751356_n.jpg',
                'date' => '20 Octobre 2024',
                'location' => 'Hôtel Radisson Sudbury'
            ],
        ]);

        $upcomingEvents = collect([
            [
                'id' => 3,
                'title' => 'Fête Nationale du Cameroun',
                'subtitle' => 'Célébration du 20 Mai',
                'description' => "Joignez-vous à nous pour célébrer la fête nationale du Cameroun avec des activités culturelles, de la musique traditionnelle et de la nourriture camerounaise.",
                'image' => 'img/476589909_122216537072196747_1503253726995860021_n.jpg',
                'date' => '20 Mai 2025',
                'location' => 'Parc Bell, Sudbury'
            ],
        ]);

        return view('home', compact('currentDate', 'pastEvents', 'upcomingEvents'));
    }

    public function events()
    {
        // Récupérer les événements depuis la base de données ou définir des données statiques
        $pastEvents = [
            [
                'id' => 1,
                'title' => 'Fête de la Culture Camerounaise 2024',
                'subtitle' => 'Retour sur la Fête de la Culture Camerounaise',
                'description' => "L'événement annuel de l'AECGS a célébré la richesse culturelle camerounaise avec des danses, de la musique et des spécialités culinaires. Un grand moment de partage et de convivialité pour tous les membres de la communauté.",
                'image' => 'img/476589909_122216537072196747_1503253726995860021_n.jpg',
                'date' => '15 Décembre 2024',
                'location' => 'Centre Communautaire de Sudbury'
            ],
            [
                'id' => 2,
                'title' => 'Soirée de Gala AECGS',
                'subtitle' => 'Une soirée mémorable',
                'description' => "Notre gala annuel a réuni plus de 200 membres de la communauté camerounaise pour une soirée de réseautage et de célébration.",
                'image' => 'img/event2.jpg',
                'date' => '20 Octobre 2024',
                'location' => 'Hôtel Radisson Sudbury'
            ],
        ];

        $upcomingEvents = [
            [
                'id' => 3,
                'title' => 'Fête Nationale du Cameroun',
                'subtitle' => 'Célébration du 20 Mai',
                'description' => "Joignez-vous à nous pour célébrer la fête nationale du Cameroun avec des activités culturelles, de la musique traditionnelle et de la nourriture camerounaise.",
                'image' => 'img/upcoming1.jpg',
                'date' => '20 Mai 2025',
                'location' => 'Parc Bell, Sudbury'
            ],
            [
                'id' => 4,
                'title' => 'Tournoi de Football AECGS',
                'subtitle' => 'Compétition sportive annuelle',
                'description' => "Participez à notre tournoi de football annuel ouvert à tous les membres de la communauté. Inscriptions ouvertes!",
                'image' => 'img/upcoming2.jpg',
                'date' => '15 Juin 2025',
                'location' => 'Terrain de sport James Jerome'
            ],
        ];

        // Données supplémentaires pour les rôles
        $totalEvents = count($pastEvents) + count($upcomingEvents);
        $totalRegistrations = 0; // À remplacer par une vraie valeur plus tard
        $upcomingCount = count($upcomingEvents);
        $pastCount = count($pastEvents);
        
        // Pour membre
        $registeredEvents = []; // À remplacer par les événements de l'utilisateur connecté

        return view('events', compact(
            'pastEvents', 
            'upcomingEvents', 
            'totalEvents', 
            'totalRegistrations', 
            'upcomingCount', 
            'pastCount', 
            'registeredEvents'
        ));
    }


    public function listDetail()
    {
        // Cette méthode affichera la page détaillée des élections
        // Pour l'instant, retournons une vue simple ou une redirection
        return view('list_detail');
    }
}
