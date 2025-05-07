<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LibraryController extends AbstractController
{
    #[Route('/library/reset', name: 'app_library_reset')]
    public function reset(EntityManagerInterface $entityManager): Response
    {
        $entityManager->createQuery('DELETE FROM App\Entity\Book b')->execute();

        $books = [
            [
                'title' => 'Wool',
                'isbn' => '9781804940822',
                'author' => 'Hugh Howey',
                'image' => 'wool.jpg',
                'language' => 'Engelska',
                'pages' => 576,
                'publishedAt' => new \DateTime('2023-04-13'),
                'publisher' => 'Cornerstone',
            ],
            [
                'title' => 'Shift',
                'isbn' => '9781804940839',
                'author' => 'Hugh Howey',
                'image' => 'shift.jpg',
                'language' => 'Engelska',
                'pages' => 592,
                'publishedAt' => new \DateTime('2023-04-13'),
                'publisher' => 'Cornerstone',
            ],
            [
                'title' => 'Dust',
                'isbn' => '9781804940846',
                'author' => 'Hugh Howey',
                'image' => 'dust.jpg',
                'language' => 'Engelska',
                'pages' => 416,
                'publishedAt' => new \DateTime('2023-04-13'),
                'publisher' => 'Cornerstone',
            ],
        ];

        foreach ($books as $data) {
            $book = new Book();
            $book->setTitle($data['title']);
            $book->setIsbn($data['isbn']);
            $book->setAuthor($data['author']);
            $book->setImage($data['image']);
            $book->setLanguage($data['language']);
            $book->setPages($data['pages']);
            $book->setPublishedAt($data['publishedAt']);
            $book->setPublisher($data['publisher']);
            $entityManager->persist($book);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Biblioteket återställdes!');
        return $this->redirectToRoute('app_book_index');
    }

    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('library/index.html.twig');
    }
}
