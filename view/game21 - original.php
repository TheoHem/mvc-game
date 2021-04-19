<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

use function Mos\Functions\{
    destroySession,
    redirectTo,
    renderView,
    renderTwigView,
    sendResponse,
    url
};

use Mos\Dice\{
    Dice,
    GraphicalDice,
    DiceHand
};

$header = $header ?? null;
$message = $message ?? null;
$diceNo = $diceNo ?? null;
$activePlayer = $activePlayer ?? null;
$reset = $reset ?? null;

$dice = new Dice(6);
$graphicalDice = new GraphicalDice;
$diceHand = new DiceHand($diceNo);

if (isset($_SESSION["playerRounds"]) === false) {
    $_SESSION["playerRounds"] = 0;
}
if (isset($_SESSION["computerRounds"]) === false) {
    $_SESSION["computerRounds"] = 0;
}

if (isset($_SESSION["playerScore"]) === false) {
    $_SESSION["playerScore"] = 0;
}
if (isset($_SESSION["computerScore"]) === false) {
    $_SESSION["computerScore"] = 0;
}

$diceHand->rollAllDice();
if ($activePlayer === "player") {
    echo "<p>Hello</p>";
    $_SESSION["playerScore"] += $diceHand->getSum();
    if ($_SESSION["playerScore"] > 21) {
        $winner = "computer";
    }
 
} else if ($activePlayer === "computer") {
    $diceNo = 1;
    $diceHand = new DiceHand($diceNo);
    $_SESSION["computerScore"] = $diceHand->computerThrow($_SESSION["playerScore"]);

    if ($_SESSION["computerScore"] > 21 || $_SESSION["computerScore"] < $_SESSION["playerScore"]) {
        $winner = "player";
    } else {
        $winner = "computer";
    }
}

if ($_SESSION["playerScore"] === $_SESSION["computerScore"] && $_SESSION["playerScore"] != 0) {
    $winner = "computer";
}

if ($_SESSION["playerScore"] === 21) {
    $winner = "player";
} 

?><h1><?= $header ?></h1>

<p><b>Current score:</b></p>
<p><b>Player: <?= $_SESSION["playerScore"] ?> (Rounds won: <?= $_SESSION["playerRounds"] ?>)</b></p>
<p><b>Computer: <?= $_SESSION["computerScore"]?> (Rounds won: <?= $_SESSION["computerRounds"] ?>)</b></p>

<p><?= $diceHand->getAllPicture() ?></p>

<?php

if (isset($winner)) {
    if ($winner === "player") {
        echo "<h2>You won!</h2>";
        $_SESSION["playerRounds"] += 1;
    } else if ($winner === "computer") {
        echo "<h2>You lost!</h2>";
        $_SESSION["computerRounds"] += 1;
    }
    echo '<form><input type="submit" value="Play again" name="Play again"><br>';
}

?>

<form>
  <input type="radio" id="one" name="dice" value="1">
  <label for="one">One dice</label><br>
  <input type="radio" id="two" name="dice" value="2">
  <label for="female">Two dice</label><br><br>
  <input type="submit" value="Throw dice!"><br><br>
 
</form>

<form>
    <input type="submit" value="Stop" name="Stop"><br><br>
</form>

<form>
    <input type="submit" value="Reset" name="Reset"><br>
</form>
<br>
