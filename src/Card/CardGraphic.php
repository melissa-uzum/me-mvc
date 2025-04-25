<?php

namespace App\Card;

class CardGraphic extends Card
{
    public function getUnicode(): string
    {
        $suit = $this->getSuit();
        $value = $this->getValue();
        $class = ($suit === '♥' || $suit === '♦') ? 'red-card' : 'black-card';

        return "<span class=\"$class\">[$value$suit]</span>";
    }
}
