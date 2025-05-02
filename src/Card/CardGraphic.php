<?php

namespace App\Card;

/**
 * Ett spelkort som returnerar HTML med färgklass för visning.
 */
class CardGraphic extends Card
{
    /**
     * Returnerar kortet som HTML med CSS klass baserat på färg.
     */
    public function getUnicode(): string
    {
        $suit = $this->getSuit();
        $value = $this->getValue();
        $class = ($suit === '♥' || $suit === '♦') ? 'red-card' : 'black-card';

        return "<span class=\"$class\">[$value$suit]</span>";
    }
}
