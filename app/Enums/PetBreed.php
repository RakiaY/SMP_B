<?php

namespace App\Enums;

enum PetBreed: string
{
    // Chiens
    case GERMAN_SHEPHERD = 'Berger Allemand';
    case LABRADOR = 'Labrador';
    case BULLDOG = 'Bulldog';
    case GOLDEN_RETRIEVER = 'Golden Retriever';
    case BEAGLE = 'Beagle';
    case POODLE = 'Caniche';
    case HUSKY = 'Husky';
    case SHIH_TZU = 'Shih Tzu';

    // Chats
    case PERSIAN = 'Persan';
    case MAINE_COON = 'Maine Coon';
    case SIAMESE = 'Siamois';
    case BENGAL = 'Bengal';
    case SPHYNX = 'Sphynx';
    case RAGDOLL = 'Ragdoll';

    // Lapins
    case HOLLAND_LOP = 'Holland Lop';
    case NETHERLAND_DWARF = 'Netherland Dwarf';
    case ANGORA = 'Angora';
    case REX = 'Rex';

    // Oiseaux
    case PARAKEET = 'Perruche';
    case CANARY = 'Canari';
    case COCKATIEL = 'Calopsitte';
    case PARROT = 'Perroquet';

    // Poissons
    case GOLDFISH = 'Poisson rouge';
    case BETTA = 'Combattant';
    case GUPPY = 'Guppy';
    case ANGELFISH = 'Scalaire';

    // Souris
    //case RAT = 'Rat';
    //case HAMSTER = 'Hamster';
    //case GUINEA_PIG = 'Cochon d\'Inde';
    //case MOUSE = 'Souris';

    // Tortues
    //case HERMANN_TURTLE = 'Tortue d’Hermann';
    //case GREEK_TURTLE = 'Tortue grecque';
    //case RED_EARED_SLIDER = 'Tortue à oreilles rouges';

    // Autres
    case FERRET = 'Furet';
    case IGUANA = 'Iguane';
    case SNAKE = 'Serpent';
    case HEDGEHOG = 'Hérisson';
    case SOURIS = 'Souris';
    case TORTUE = 'Tortue';

    public static function getBreedsByType(string $type): array
    {
        return match ($type) {
            PetType::Chien->value => [
                self::GERMAN_SHEPHERD->value,
                self::LABRADOR->value,
                self::BULLDOG->value,
                self::GOLDEN_RETRIEVER->value,
                self::BEAGLE->value,
                self::POODLE->value,
                self::HUSKY->value,
                self::SHIH_TZU->value,
            ],
            PetType::Chat->value => [
                self::PERSIAN->value,
                self::MAINE_COON->value,
                self::SIAMESE->value,
                self::BENGAL->value,
                self::SPHYNX->value,
                self::RAGDOLL->value,
            ],
            PetType::Lapin->value => [
                self::HOLLAND_LOP->value,
                self::NETHERLAND_DWARF->value,
                self::ANGORA->value,
                self::REX->value,
            ],
            PetType::Oiseau->value => [
                self::PARAKEET->value,
                self::CANARY->value,
                self::COCKATIEL->value,
                self::PARROT->value,
            ],
            PetType::Poisson->value => [
                self::GOLDFISH->value,
                self::BETTA->value,
                self::GUPPY->value,
                self::ANGELFISH->value,
            ],
            
             PetType::Autre->value => [
                // Cobaye
                'Abyssin',
                self::ANGORA->value,
                'Péruvien',
                'Texel',
                self::REX->value,

                // Furet
                'Albinos',
                'Zibeline',
                'Champagne',
                self::ANGORA->value,

                // Perroquet
                'Gris du Gabon',
                'Ara bleu',
                'Perruche ondulée',
                'Pionus',
                'Caique',

                // Tortue
                'de Hermann',
                'étoilée',
                'sulcata',
                'boîte d’eau',

                // Serpent
                'Python royal',
                'Couleuvre cornue',
                'Boa constrictor',
                'Couleuvre de Rat',
                'Couleuvre royale',

                // Chinchilla
                'Standard',
                'White mosaic',
                'Ebony',
                'Sapphire',
                'Violet',

                // Souris
                'Fancy mouse',
                'Black mouse',
                'Golden mouse',
                'Dalmatian mouse',
            ],
            default => [],
        };
    }
}
