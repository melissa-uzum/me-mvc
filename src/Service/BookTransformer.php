<?php

namespace App\Service;

use App\Entity\Book;

/**
 * Omvandlar bokentiteter till arrayer för presentation eller API-respons.
 */
class BookTransformer
{
    /**
     * Omvandlar ett bokobjekt till en array.
     *
     * @param Book $book Boken som ska omvandlas.
     * @return array Associerad array med bokdata.
     */
    public function transform(Book $book): array
    {
        return [
            'title' => $book->getTitle(),
            'isbn' => $book->getIsbn(),
            'author' => $book->getAuthor(),
            'image' => $book->getImage(),
            'language' => $book->getLanguage(),
            'pages' => $book->getPages(),
            'published_at' => $book->getPublishedAt()?->format('Y-m-d'),
            'publisher' => $book->getPublisher(),
        ];
    }

    /**
     * Omvandlar en lista av bokobjekt till en lista av arrayer.
     *
     * @param Book[] $books Lista av bokobjekt.
     * @return array Lista av associerade arrayer med bokdata.
     */
    public function transformMany(array $books): array
    {
        return array_map([$this, 'transform'], $books);
    }
}
