<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Kontroller för att visa och rensa sessionen (för felsökning).
 */
class SessionController extends AbstractController
{
    #[Route('/session', name: 'session_view')]
    public function view(SessionInterface $session): Response
    {
        $data = [];

        foreach ($session->all() as $key => $value) {
            if (is_object($value)) {
                $data[$key] = method_exists($value, 'getCards') ? $value->getCards()
                            : (method_exists($value, '__toString') ? (string) $value
                            : 'Objekt av typen ' . get_class($value));
            } else {
                $data[$key] = $value;
            }
        }

        return $this->render('card/session.html.twig', ['session' => $data]);
    }

    #[Route('/session/delete', name: 'session_delete')]
    public function delete(SessionInterface $session): Response
    {
        $session->clear();
        $this->addFlash('notice', 'Sessionen har rensats.');
        return $this->redirectToRoute('session_view');
    }
}
