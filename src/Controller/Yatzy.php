<?php

declare(strict_types=1);

namespace Mos\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;


use function Mos\Functions\{
    destroySession,
    redirectTo,
    renderView,
    renderTwigView,
    sendResponse,
    url,
    testFunc
};

use Mos\Functions\Dice;
use Mos\Functions\GraphicalDice;

use Mos\Functions\YatzyGame;

//use Mos\Dice\Dice;


/**
 * Controller for the Game21 route.
 */
class Yatzy
{
    public function __invoke(): ResponseInterface
    {   
        $psr17Factory = new Psr17Factory();
        
        $path = substr($_SERVER['REQUEST_URI'], 29);

        $dice = new Dice(6);

        $yatzy = new YatzyGame;
        

        $data = [
            "header" => "Yatzy",
            "message" => "This is a game of yatzy!",
            //"yatzy" => $yatzy,
            /*
            "sides" => $yatzy->get_dice(),
            "last_throw" => $yatzy->roll_all_dice(),
            "saved_throw" => $yatzy->save_throw($yatzy->dice[1], $yatzy->dice[4],$yatzy->dice[2]),
            "pictures" => $yatzy->get_all_pictures(),
            */
        ];
        
        $body = renderView("layout/yatzy.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
}
