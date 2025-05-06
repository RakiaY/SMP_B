<?php

namespace App\Enums;

enum petType: string
{
    case Chat = 'Chat';
    case Chien = 'Chien';
    case Lapin = 'Lapin';
    case Oiseau = 'Oiseau';
    case Poisson = 'Poisson';
    case Souris ='Souris';
    case Tortue = 'Tortue';
    case Autre = 'Autre'; 


    
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }// Pour les animaux non spécifiés
}