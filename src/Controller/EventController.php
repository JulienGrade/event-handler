<?php

namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EventController extends AbstractController
{
    #[Route('/les-evenements', name: 'app_event')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $events = $entityManager->getRepository(Event::class)->findAll();
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/les-evenements/modifier/{id}', name: 'app_event_update', methods: ['GET', 'POST'])]
    public function updateEvent(EntityManagerInterface $entityManager, int $id): Response
    {
        // Récupération d'un événement
        $event = $entityManager->getRepository(Event::class)->findOneById($id); // Exemple avec ID = 1

        if (!$event) {
            throw $this->createNotFoundException('Event not found.');
        }

        // Vérification de la date
        if ($event->getDate() === null) {
            throw new RuntimeException('Event date is not set.');
        }

        // Formatage de la date
        $formattedDate = $event->getDate()->format('Y-m-d H:i:s');

        // Retour d'une réponse avec la date formatée
        return new Response("The event date is: $formattedDate");
    }
}
