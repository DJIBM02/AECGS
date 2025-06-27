<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        // Ici, vous pourriez récupérer des données de la base de données
        // par exemple, les membres de l'équipe
        $teamMembers = [
            [
                'name' => 'xxx',
                'position' => 'Président de l\'AECGS',
                'image' => 'img/user.jpeg',
                'socials' => [
                    'facebook' => '#',
                    'twitter' => '#',
                    'linkedin' => '#',
                    'instagram' => '#',
                ]
            ],
            [
                'name' => 'xxx',
                'position' => 'Vice-Présidente',
                'image' => 'img/user.jpeg',
                'socials' => [
                    'facebook' => '#',
                    'twitter' => '#',
                    'linkedin' => '#',
                    'instagram' => '#',
                ]
            ],
            [
                'name' => 'xxx',
                'position' => 'Secrétaire Générale',
                'image' => 'img/user.jpeg',
                'socials' => [
                    'facebook' => '#',
                    'twitter' => '#',
                    'linkedin' => '#',
                    'instagram' => '#',
                ]
            ],
            [
                'name' => 'Rxxx',
                'position' => 'Secrétaire Adjoint #1',
                'image' => 'img/user.jpeg',
                'socials' => [
                    'facebook' => '#',
                    'twitter' => '#',
                    'linkedin' => '#',
                    'instagram' => '#',
                ]
            ]
        ];

        $objectives = [
            [
                'icon' => 'fas fa-users',
                'title' => 'Rassembler la Communauté',
                'description' => 'Rassembler tous les Camerounaises et Camerounais résidant dans le Grand Sudbury et ses environs dans un esprit de convivialité, de fraternité et d\'entraide.'
            ],
            [
                'icon' => 'fas fa-handshake',
                'title' => 'Accompagnement des Nouveaux Arrivants',
                'description' => 'Accueillir et accompagner les nouveaux arrivants camerounais au Grand Sudbury pour favoriser leur intégration dans la communauté.'
            ],
            // Ajoutez les autres objectifs de la même façon
        ];

        return view('about', compact('teamMembers', 'objectives'));
    }
}
