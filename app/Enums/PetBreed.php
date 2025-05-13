<?php
namespace App\Enums;

enum PetBreed: string
{
    // Chiens
    case GERMAN_SHEPHERD = 'Berger Allemand';
    case LABRADOR = 'Labrador';
        case BULLDOG = 'Bulldog';



     
    
    // Chats
    case PERSIAN = 'Persan';
        case Maine_Coon = 'Maine Coon';

    case SIAMESE = 'Siamois';
    case RAT = 'Rat';

    case HAMSTER = 'Hamster';


    public static function getBreedsByType(string $type): array
    {
        return match ($type) {
            PetType::Chien->value => [
                self::GERMAN_SHEPHERD->value,
                self::LABRADOR->value,
                self::BULLDOG->value,

            ],
            PetType::Chat->value => [
                self::PERSIAN->value,
                self::SIAMESE->value,
                                self::Maine_Coon->value,

            ],
            PetType::Souris->value => [
                self::RAT->value,
                self::HAMSTER->value,
            ],
            default => [],
        };
    }
}