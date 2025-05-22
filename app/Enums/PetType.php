<?php

namespace App\Enums;

enum petType: string
{
    case cat = 'Chat';
    case dog = 'dog';
    case rabbit = 'Lapin';
    case bird = 'Oiseau';
    case fish = 'Poisson';
    case mouse ='Souris';
    case autre = 'Autre'; 


    
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }// Pour les animaux non spécifiés
}