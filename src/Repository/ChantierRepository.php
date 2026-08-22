<?php

namespace App\Repository;

use App\Entity\Chantier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chantier>
 */
class ChantierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chantier::class);
    }

    /**
     * Récupère tous les chantiers avec leurs équipements en une seule requête
     * (évite le problème des N+1 requêtes grâce à un JOIN + fetch eager).
     *
     * @return Chantier[]
     */
    public function findAllWithEquipements(): array
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.equipements', 'e')
            ->addSelect('e')
            ->orderBy('c.dateDebut', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
