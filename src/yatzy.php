<?php

declare(strict_types=1);

namespace Mos\Yatzy;


use Mos\Functions\Dice;

class Yatzy {
    public $dice;
    public $last_throw;

    function __construct() {
        $this->dice = new Dice;
        $this->$last_thrrow = $dice.roll_dice();
    }

}

