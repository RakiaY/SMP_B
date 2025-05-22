<?php

namespace App\Enums;

enum petType: string
{
        case Chien = 'Chien';

    case Chat = 'Chat';
    case Lapin = 'Lapin';
    case Oiseau = 'Oiseau';
    case Poisson = 'Poisson';
    case Autre = 'Autre'; 
    


    
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }// Pour les animaux non spécifiés
}