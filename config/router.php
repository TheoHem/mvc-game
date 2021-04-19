<?php

/**
 * Load the routes into the router, this file is included from
 * `htdocs/index.php` during the bootstrapping to prepare for the request to
 * be handled.
 */

declare(strict_types=1);

use FastRoute\RouteCollector;

$router = $router ?? null;

$router->addRoute("GET", "/test", function () {
    // A quick and dirty way to test the router or the request.
    return "Testing response";
});

$router->addRoute("GET", "/", "\Mos\Controller\Index");
$router->addRoute("GET", "/debug", "\Mos\Controller\Debug");
$router->addRoute("GET", "/twig", "\Mos\Controller\TwigView");
$router->addRoute("GET", "/testing", "\Mos\Controller\Testing");

$router->addRoute("GET", "/game21", "\Mos\Controller\Game21");
$router->addRoute("GET", "/game21?dice=1", "\Mos\Controller\Game21");
$router->addRoute("GET", "/game21?dice=2", "\Mos\Controller\Game21");
$router->addRoute("GET", "/game21?", "\Mos\Controller\Game21");
$router->addRoute("GET", "/game21?Stop=Stop", "\Mos\Controller\Game21");
$router->addRoute("GET", "/game21?Reset=Reset", "\Mos\Controller\Game21");
$router->addRoute("GET", "/game21?Play+again=Play+again", "\Mos\Controller\Game21");
$router->addRoute("GET", "/game21?Play+again=Play+again&dice=0", "\Mos\Controller\Game21");
$router->addRoute("GET", "/game21?Play+again=Play+again&dice=1", "\Mos\Controller\Game21");
$router->addRoute("GET", "/game21?Play+again=Play+again&dice=2", "\Mos\Controller\Game21");


$router->addRoute("GET", "/yatzy", "\Mos\Controller\Yatzy");
$router->addRoute("GET", "/yatzy?start=Start", "\Mos\Controller\Yatzy");
$router->addRoute("POST", "/yatzy", "\Mos\Controller\Yatzy");

$router->addGroup("/session", function (RouteCollector $router) {
    $router->addRoute("GET", "", ["\Mos\Controller\Session", "index"]);
    $router->addRoute("GET", "/destroy", ["\Mos\Controller\Session", "destroy"]);
});

$router->addGroup("/some", function (RouteCollector $router) {
    $router->addRoute("GET", "/where", ["\Mos\Controller\Sample", "where"]);
});

$router->addGroup("/form", function (RouteCollector $router) {
    $router->addRoute("GET", "/view", ["\Mos\Controller\Form", "view"]);
    $router->addRoute("POST", "/process", ["\Mos\Controller\Form", "process"]);
});
