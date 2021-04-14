<?php

declare(strict_types=1);

namespace Mos\Router;

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
use Mos\Functions\TestClass;

/**
 * Class Router.
 */
class Router
{
    public static function dispatch(string $method, string $path): void
    {
        if ($method === "GET" && $path === "/") {
            $data = [
                "header" => "Index page",
                "message" => "Hello, this is the index page, rendered as a layout.",
            ];
            $body = renderView("layout/page.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/session") {
            $body = renderView("layout/session.php");
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/session/destroy") {
            destroySession();
            redirectTo(url("/session"));
            return;
        } else if ($method === "GET" && $path === "/debug") {
            $body = renderView("layout/debug.php");
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/twig") {
            $data = [
                "header" => "Twig page",
                "message" => "Hey, edit this to do it youreself!",
            ];
            $body = renderTwigView("index.html", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/some/where") {
            $data = [
                "header" => "Rainbow page",
                "message" => "Hey, edit this to do it youreself!",
            ];
            $body = renderView("layout/page.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/test") {
            $testclass = new TestClass;
            $dice = new Dice;

            $data = [
                "header" => "Test page",
                "message" => $testclass->testFunc(),
                "dice" => $dice,
            ];
            $body = renderView("layout/test.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET"
                    && ($path === "/game21"
                    || $path ==="/game21?dice=1"
                    || $path ==="/game21?dice=2"
                    || $path === "/game21?"
                    || $path === "/game21?Stop=Stop"
                    || $path === "/game21?Reset=Reset" 
                    || $path === "/game21?Play+again=Play+again"
                    || $path === "/game21?Play+again=Play+again&dice=0"
                    || $path === "/game21?Play+again=Play+again&dice=1"
                    || $path === "/game21?Play+again=Play+again&dice=2")) {
            
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
            sendResponse($body);
            return;
        }

        $data = [
            "header" => "404",
            "message" => "The page you are requesting is not here. You may also checkout the HTTP response code, it should be 404.",
        ];
        $body = renderView("layout/page.php", $data);
        sendResponse($body, 404);
    }
}
