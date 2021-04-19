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

/**
 * Controller for the Game21 route.
 */
class Game21
{
    public function __invoke(): ResponseInterface
    {   
        $psr17Factory = new Psr17Factory();

        
        function console_log( $data ){
            echo '<script>';
            echo 'console.log('. json_encode( $data ) .')';
            echo '</script>';
        }
        
        $path = substr($_SERVER['REQUEST_URI'], 29);

        
        if ($path ==="/game21?dice=1") {
            $diceNo = 1;
        } else if ($path ==="/game21?dice=2") {
            $diceNo = 2;
        } else {
            $diceNo = 0;
        }

        if (isset($_SESSION["stopCounter"]) === false) {
            $_SESSION["stopCounter"] = 0;
        }

        
        if ($path === "/game21?Stop=Stop") {
            $_SESSION["stopCounter"] += 1;
        }

        if ($path === "/game21?Reset=Reset" ) {
            destroySession();
            redirectTo(url("/game21"));
        }

        if ($path === "/game21?Play+again=Play+again") {
            $_SESSION["stopCounter"] = 0;
            $_SESSION["playerScore"] = 0;
            $_SESSION["computerScore"] = 0;
            header('Refresh:0; url=' . url("/game21"));
        }

        if (isset($_SESSION["stopCounter"]) === false) {
            $_SESSION["stopCounter"] = 0;
        }

        if ($_SESSION["stopCounter"] % 2 === 0) {
            $_SESSION["activePlayer"] = "player";
        } else {
            $_SESSION["activePlayer"] = "computer";
        }

        $data = [
            "header" => "Game 21",
            "message" => "This is a game of 21!",
            "diceNo" => $diceNo,
            "activePlayer" => $_SESSION["activePlayer"],
        ];
        
        $body = renderView("layout/game21.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
}
