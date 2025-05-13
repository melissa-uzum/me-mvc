<?php

namespace App\Service;

use App\Entity\Book;
use App\Form\BookForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Tjänst som hanterar formulär för att skapa och uppdatera böcker.
 */
class BookFormHandler
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private EntityManagerInterface $em,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    /**
     * Hanterar ett formulär för att skapa eller uppdatera en bok.
     *
     * @param Request $request HTTP-begäran.
     * @param Book $book Boken som ska hanteras.
     * @param string $template Namnet på templatet som ska renderas.
     * @param bool $isNew Om boken är ny eller existerande.
     * @param callable $render Funktion för att rendera svar vid ogiltigt formulär.
     * @return SymfonyResponse HTTP-svar.
     */
    public function handle(Request $request, Book $book, string $template, bool $isNew, callable $render): SymfonyResponse
    {
        $form = $this->formFactory->create(BookForm::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($isNew) {
                $this->em->persist($book);
            }
            $this->em->flush();

            return new RedirectResponse($this->urlGenerator->generate('app_book_index'));
        }

        return $render($template, [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }
}
