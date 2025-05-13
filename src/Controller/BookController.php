<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookForm;
use App\Repository\BookRepository;
use App\Service\BookFormHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/book')]
final class BookController extends AbstractController
{
    #[Route(name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, BookFormHandler $handler): Response
    {
        return $handler->handle($request, new Book(), 'book/new.html.twig', true, fn($tpl, $data) => $this->render($tpl, $data));
    }

    #[Route('/{id}', name: 'app_book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', ['book' => $book]);
    }

    #[Route('/{id}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, BookFormHandler $handler): Response
    {
        return $handler->handle($request, $book, 'book/edit.html.twig', false, fn($tpl, $data) => $this->render($tpl, $data));
    }

    #[Route('/{id}', name: 'app_book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $em): Response
    {
        $token = $request->getPayload()->getString('_token');

        if ($this->isCsrfTokenValid('delete' . $book->getId(), $token)) {
            $em->remove($book);
            $em->flush();
        }

        return $this->redirectToRoute('app_book_index', [], Response::HTTP_SEE_OTHER);
    }
}
