<?php

namespace App\Card;

class CardGraphic extends Card
{
    public function getUnicode(): string
    {
        return '['.$this->getValue().$this->getSuit().']';
    }
}
