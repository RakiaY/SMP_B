<?php
namespace App\Enums;

enum PetBreed: string
{
    // Chiens
    case GERMAN_SHEPHERD = 'Berger Allemand';
    case LABRADOR = 'Labrador';
    
    // Chats
    case PERSIAN = 'Persan';
    case SIAMESE = 'Siamois';
    case RAT = 'Rat';

    case HAMSTER = 'Hamster';


    public static function getBreedsByType(string $type): array
    {
        return match ($type) {
            PetType::Chien->value => [
                self::GERMAN_SHEPHERD->value,
                self::LABRADOR->value,
            ],
            PetType::Chat->value => [
                self::PERSIAN->value,
                self::SIAMESE->value,
            ],
            PetType::Souris->value => [
                self::RAT->value,
                self::HAMSTER->value,
            ],
            default => [],
        };
    }
}