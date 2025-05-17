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

    // Rongeurs (Souris)
    case RAT = 'Rat';
    case HAMSTER = 'Hamster';
    case GUINEA_PIG = 'Cochon d\'Inde';
    case MOUSE = 'Souris';

    // Tortues
    case HERMANN_TURTLE = 'Tortue d’Hermann';
    case GREEK_TURTLE = 'Tortue grecque';
    case RED_EARED_SLIDER = 'Tortue à oreilles rouges';

    // Autres
    case FERRET = 'Furet';
    case IGUANA = 'Iguane';
    case SNAKE = 'Serpent';
    case HEDGEHOG = 'Hérisson';

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
            PetType::Souris->value => [
                self::RAT->value,
                self::HAMSTER->value,
                self::GUINEA_PIG->value,
                self::MOUSE->value,
            ],
            PetType::Tortue->value => [
                self::HERMANN_TURTLE->value,
                self::GREEK_TURTLE->value,
                self::RED_EARED_SLIDER->value,
            ],
            PetType::Autre->value => [
                self::FERRET->value,
                self::IGUANA->value,
                self::SNAKE->value,
                self::HEDGEHOG->value,
            ],
            default => [],
        };
    }
}
