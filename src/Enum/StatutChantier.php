<?php

namespace App\Enum;

/**
 * Enum représentant les statuts possibles d'un chantier.
 * Un enum plutôt qu'un simple string évite les fautes de frappe
 * ("Terminé" vs "termine" vs "TERMINE") et centralise les valeurs autorisées.
 */
enum StatutChantier: string
{
    case EN_ATTENTE = 'En attente';
    case EN_COURS = 'En cours';
    case TERMINE = 'Terminé';
}
