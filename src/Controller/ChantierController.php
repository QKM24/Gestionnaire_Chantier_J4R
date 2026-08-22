<?php

namespace App\Controller;

use App\Entity\Chantier;
use App\Enum\StatutChantier;
use App\Repository\ChantierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChantierController extends AbstractController
{
    #[Route('/', name: 'chantier_index', methods: ['GET'])]
    public function index(ChantierRepository $chantierRepository): Response
    {
        return $this->render('chantier/index.html.twig', [
            'chantiers' => $chantierRepository->findAllWithEquipements(),
        ]);
    }

    /**
     * Endpoint AJAX : passe un chantier au statut "Terminé".
     * Répond toujours en JSON (jamais en redirection/HTML), car appelé en fetch() depuis le JS.
     */
    #[Route('/chantier/{id}/terminer', name: 'chantier_terminer', methods: ['POST'])]
    public function terminer(?Chantier $chantier, EntityManagerInterface $em): JsonResponse
    {
        // Cas d'erreur 1 : le chantier n'existe pas (id invalide / déjà supprimé)
        if (!$chantier) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Ce chantier est introuvable. Il a peut-être déjà été supprimé.',
            ], 404);
        }

        // Cas d'erreur 2 : le chantier est déjà terminé
        if ($chantier->getStatut() === StatutChantier::TERMINE) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Ce chantier est déjà marqué comme terminé.',
            ], 409);
        }

        try {
            $chantier->setStatut(StatutChantier::TERMINE);
            $em->flush();
        } catch (\Throwable $e) {
            // Cas d'erreur 3 : problème inattendu (ex. base de données injoignable)
            return new JsonResponse([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour. Merci de réessayer.',
            ], 500);
        }

        return new JsonResponse([
            'success' => true,
            'id' => $chantier->getId(),
            'statut' => $chantier->getStatut()->value,
            'message' => sprintf('Le chantier "%s" a été marqué comme terminé.', $chantier->getNom()),
        ]);
    }
}
