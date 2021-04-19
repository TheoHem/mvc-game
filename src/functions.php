<?php

declare(strict_types=1);

namespace Mos\Functions;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Functions.
 */


/**
 * Get the route path representing the page being requested.
 *
 * @return string with the route path requested.
 */
function getRoutePath(): string
{
    $offset = strlen(dirname($_SERVER["SCRIPT_NAME"]));
    $path   = substr($_SERVER["REQUEST_URI"], $offset);

    return $path;
}



/**
 * Render the view and return its rendered content.
 *
 * @param string $template to use when rendering the view.
 * @param array  $data     send to as variables to the view.
 *
 * @return string with the route path requested.
 */
function renderView(
    string $template,
    array $data = []
): string {
    extract($data);

    ob_start();
    require INSTALL_PATH . "/view/$template";
    $content = ob_get_contents();
    ob_end_clean();

    return ($content ? $content : "");
}



/**
 * Use Twig to render a view and return its rendered content.
 *
 * @param string $template to use when rendering the view.
 * @param array  $data     send to as variables to the view.
 *
 * @return string with the route path requested.
 */
function renderTwigView(
    string $template,
    array $data = []
): string {
    static $loader = null;
    static $twig = null;

    if (is_null($twig)) {
        $loader = new FilesystemLoader(
            INSTALL_PATH . "/view/twig"
        );
        // $twig = new \Twig\Environment($loader, [
        //     "cache" => INSTALL_PATH . "/cache/twig",
        // ]);
        $twig = new Environment($loader);
    }

    return $twig->render($template, $data);
}



/**
 * Send a response to the client.
 *
 * @param int    $status   HTTP status code to send to client.
 *
 * @return void
 */
function sendResponse(string $body, int $status = 200): void
{
    http_response_code($status);
    echo $body;
}



/**
 * Redirect to an url.
 *
 * @param string $url where to redirect.
 *
 * @return void
 */
function redirectTo(string $url): void
{
    http_response_code(200);
    header("Location: $url");
}



/**
 * Create an url into the website using the path and prepend the baseurl
 * to the current website.
 *
 * @param string $path to use to create the url.
 *
 * @return string with the route path requested.
 */
function url(string $path): string
{
    return getBaseUrl() . $path;
}



/**
 * Get the base url from the request, relative to the htdoc/ directory.
 *
 * @return string as the base url.
 */
function getBaseUrl()
{
    static $baseUrl = null;

    if ($baseUrl) {
        return $baseUrl;
    }

    $scriptName = rawurldecode($_SERVER["SCRIPT_NAME"]);
    $path = rtrim(dirname($scriptName), "/");

    // Prepare to create baseUrl by using currentUrl
    $parts = parse_url(getCurrentUrl());

    // Build the base url from its parts
    $siteUrl = "{$parts["scheme"]}://{$parts["host"]}"
        . (isset($parts["port"])
            ? ":{$parts["port"]}"
            : "");
    $baseUrl = $siteUrl . $path;

    return $baseUrl;
}



/**
 * Get the current url of the request.
 *
 * @return string as current url.
 */
function getCurrentUrl(): string
{
    $scheme = $_SERVER["REQUEST_SCHEME"];
    $server = $_SERVER["SERVER_NAME"];

    $port  = $_SERVER["SERVER_PORT"];
    $port  = ($port === "80")
        ? ""
        : (($port === 443 && $_SERVER["HTTPS"] === "on")
            ? ""
            : ":" . $port);

    $uri = rtrim(rawurldecode($_SERVER["REQUEST_URI"]), "/");

    $url  = htmlspecialchars($scheme) . "://";
    $url .= htmlspecialchars($server)
        . $port . htmlspecialchars(rawurldecode($uri));

    return $url;
}



/**
 * Destroy the session.
 *
 * @return void
 */
function destroySession(): void
{
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
}

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
        if ($this->lastThrow) {
            return '<img src="' . url('/img/dice' . strval($this->lastThrow) . '.jpg') . '">';
        }
    }
}

class YatzyGame {

    public $dice = [];
    public $last_throw = [];
    public $saved_dice_values = [];
    public $dice_pictures = [];

    function __construct() {
        for ($i = 0; $i < 5; $i++) {
            array_push($this->dice, new GraphicalDice());
        }
    }

    function change_dice($num_of_dice) {
        $this->dice = [];
        for ($i = 0; $i < $num_of_dice; $i++) {
            array_push($this->dice, new GraphicalDice());
        }
    }

    function get_dice() {
        return $this->dice;
    }

    function get_last_throw() {
        return $this->last_throw;
    }

    function get_dice_values() {
        $tmp_arr = [];
        foreach ($this->dice as $dice) {
            array_push($tmp_arr, $dice->get_lastThrow());
        }

        return $tmp_arr;
    }

    function roll_all_dice() {
        $this->last_throw = [];
        foreach ($this->dice as $dice) {
            array_push($this->last_throw, $dice->rollDice());
        }
        return $this->last_throw;
    }

    function roll_some_dice($dice_numbers) {
        foreach ($dice_numbers as $number) {
            $this->dice[intval($number)-1]->rollDice();
        }
    }

    function save_throw($saved_dice) {
        $saved_dice = func_get_args();
        foreach ($saved_dice as $dice) {
            array_push($this->saved_dice_values, $dice->lastThrow);
        }
        return $this->saved_dice_values;
    }

    function get_all_pictures() {
        $this->dice_pictures = [];
        foreach ($this->dice as $dice) {
            array_push($this->dice_pictures, $dice->get_picture());
        }
        return $this->dice_pictures;
    }

}

