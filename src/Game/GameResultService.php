<?php

namespace App\Game;

/**
 * Returnerar resultatmeddelande för spelet 21 baserat på spelets tillstånd och vinnare.
 */
class GameResultService
{
    /**
     * Genererar ett meddelande baserat på om spelet är slut och vem som vann.
     *
     * @param bool $isGameOver Om spelet är avslutat.
     * @param string $winner Vinnaren: 'player', 'bank' eller 'none'.
     * @return string Meddelande att visa för användaren.
     */
    public function getResultMessage(bool $isGameOver, string $winner): string
    {
        if (!$isGameOver) {
            return "Spelet pågår...";
        }

        return match ($winner) {
            'player' => "Spelaren vinner!",
            'bank' => "Banken vinner!",
            'none' => "Oavgjort.",
            default => "Okänt resultat.",
        };
    }
}
