<?php

namespace App\Game;

/**
 * Hanterar spelarens pengar i spelet 21.
 */
class Wallet
{
    private int $amount;

    /**
     * Initierar plånboken med ett startbelopp.
     *
     * @param int $initialAmount Startbeloppet (standard är 100).
     */
    public function __construct(int $initialAmount = 100)
    {
        $this->amount = $initialAmount;
    }

    /**
     * Återställer beloppet vid uppvakning från session.
     */
    public function __wakeup(): void
    {
        $this->amount ??= 100;
    }

    /**
     * Returnerar aktuellt belopp.
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Kontrollerar om spelaren har råd med ett visst belopp.
     *
     * @param int $cost Kostnaden att kontrollera.
     * @return bool True om spelaren har råd, annars false.
     */
    public function canAfford(int $cost): bool
    {
        return $cost > 0 && $this->amount >= $cost;
    }

    /**
     * Lägger till pengar i plånboken.
     *
     * @param int $value Belopp att lägga till.
     */
    public function add(int $value): void
    {
        $this->amount += $value;
    }

    /**
     * Drar pengar från plånboken.
     *
     * @param int $value Belopp att dra bort.
     */
    public function subtract(int $value): void
    {
        $this->amount -= $value;
    }

    /**
     * Kontrollerar om plånboken är tom.
     *
     * @return bool True om tom, annars false.
     */
    public function isEmpty(): bool
    {
        return $this->amount <= 0;
    }

    /**
     * Justerar beloppet uppåt eller nedåt.
     *
     * @param int $amount Justeringsbelopp.
     */
    public function adjust(int $amount): void
    {
        $this->amount += $amount;
    }
}
