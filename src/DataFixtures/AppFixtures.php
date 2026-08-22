<?php

namespace App\DataFixtures;

use App\Entity\Chantier;
use App\Entity\Equipement;
use App\Enum\StatutChantier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $echafaudage = (new Equipement())->setNom('Échafaudage 10m')->setQuantite(4);
        $betonniere = (new Equipement())->setNom('Bétonnière')->setQuantite(2);
        $perceuse = (new Equipement())->setNom('Perceuse à percussion')->setQuantite(10);
        $grue = (new Equipement())->setNom('Grue mobile')->setQuantite(1);
        $compresseur = (new Equipement())->setNom("Compresseur d'air")->setQuantite(3);

        foreach ([$echafaudage, $betonniere, $perceuse, $grue, $compresseur] as $equipement) {
            $manager->persist($equipement);
        }

        $chantier1 = (new Chantier())
            ->setNom('Rénovation Façade')
            ->setAdresse('12 rue des Lilas, Tunis')
            ->setDateDebut(new \DateTime('2026-06-01'))
            ->setDateFin(new \DateTime('2026-09-01'))
            ->setStatut(StatutChantier::EN_COURS);
        $chantier1->addEquipement($echafaudage);
        $chantier1->addEquipement($compresseur);

        $chantier2 = (new Chantier())
            ->setNom('Construction Villa El Manar')
            ->setAdresse('45 avenue Habib Bourguiba, Tunis')
            ->setDateDebut(new \DateTime('2026-03-15'))
            ->setDateFin(new \DateTime('2026-12-15'))
            ->setStatut(StatutChantier::EN_ATTENTE);
        $chantier2->addEquipement($betonniere);
        $chantier2->addEquipement($grue);
        $chantier2->addEquipement($perceuse);

        $chantier3 = (new Chantier())
            ->setNom('Réfection Toiture Immeuble B')
            ->setAdresse('3 rue de Marseille, Tunis')
            ->setDateDebut(new \DateTime('2026-01-10'))
            ->setDateFin(new \DateTime('2026-02-20'))
            ->setStatut(StatutChantier::TERMINE);
        $chantier3->addEquipement($echafaudage);

        foreach ([$chantier1, $chantier2, $chantier3] as $chantier) {
            $manager->persist($chantier);
        }

        $manager->flush();
    }
}
