<?php

declare(strict_types=1);

namespace Theohem\Dice;

use function Mos\Functions\url;

class Dice {
    public $sides = 6;
    public $lastThrow;
    
    function __construct($setSides) {
        $this->sides = $setSides;
    }

    function set_lastThrow($setThrow) {
        $this->lastThrow = $setThrow;
    }

    function get_lastThrow() {
        return $this->lastThrow;
    }

    function rollDice() {
        $throw = round(($this->sides * rand(10, 100)) / 100);
        $this->set_lastThrow($throw);
        return $throw;
    }

    function set_sides($sides) {
        $this->sides = $sides;
    }

    function get_sides() {
        return $this->sides;
    }
}

class GraphicalDice extends Dice {
    
    function __construct() {
        $this->sides = 6;
    }

    function get_picture() {
        return '<img src="' . url('/img/dice' . strval($this->lastThrow) . '.jpg') . '">';
    }
}

class DiceHand {
    public $numberOfDice;
    public $diceArray = [];

    function __construct($setNumberOfDice) {
        $this->numberOfDice = $setNumberOfDice;
        
        for ($i = 0; $i < $this->numberOfDice; $i++) {
            array_push($this->diceArray, new GraphicalDice);
        }
        
    }

    function get_diceArray() {
        return $this->diceArray;
    }

    function get_numberOfDice() {
       return $this->numberOfDice;
    }

    function rollAllDice() {
        foreach ($this->diceArray as $dice) {
            $dice->rollDice();
        }
    }

    function printAllLastThrow() {
        foreach ($this->diceArray as $dice) {
            echo $dice->get_lastThrow();
        }
    }

    function getLastThrowOneDice($index) {
        return $diceArray[$index]->get_lastThrow();
    }

    function getAllPicture() {
        foreach ($this->diceArray as $dice) {
            echo $dice->get_picture();
        }
    }

    function getSum() {
        $sum = 0;
        foreach ($this->diceArray as $dice) {
            $sum += $dice->get_lastThrow();
        }
        return $sum;
    }

    function computerThrow($playerScore) {
        $totalSum = 0;
        for ($i = 0; $i < 20; $i++) {
            $this->rollAllDice();
            $totalSum += $this->getSum();
            if ($totalSum > $playerScore || $totalSum > 21) {
                break;
            }
        }
        return $totalSum;
    }

}