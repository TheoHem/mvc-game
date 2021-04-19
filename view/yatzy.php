<?php

declare(strict_types=1);

use Mos\Functions\YatzyGame;

$yatzy = $yatzy ?? null;
$header = $header ?? null;
$message = $message ?? null;

$yatzy = $_SESSION["yatzy"];

?><h1><?= $header ?></h1>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($_SESSION["round"] == 7) {
            echo "<h2>Game finished!</h2>";
            echo "<h4>Points: " . $_SESSION["points"] . "</h4>";
            if ($_SESSION["points"] > 63) {
                echo "<h4>You got the bonus!</h4>";
                $_SESSION["points"] += 50;
            } else {
                echo "<h4>You did not get the bonus!</h4>";
            }  

            echo "Final score: " . $_SESSION["points"] . " points.";

        } else {
            echo "<h3>Round " . $_SESSION["round"] . ": get " . $_SESSION["round"] . "'s!</h3>";
            echo "<h4>Points: " . $_SESSION["points"] . "</h4>";

            $roll_arr = [];
            foreach ($_POST as $value) {
                $counter = 1;
                array_push($roll_arr, $value);
                $counter++;
            }
            $_SESSION["to_roll"] = $roll_arr;
            if($_SESSION["round_counter"] === 0) {
                $_SESSION["to_roll"] = [1, 2, 3, 4, 5];
            }

            $yatzy->roll_some_dice($_SESSION["to_roll"]);
            $counter = 0;
            foreach ($yatzy->get_all_pictures() as $picture) {
                echo $picture;
                $counter++;
            }

            echo '<form method="post">';
            
            $counter = 1;
            
            if ($_SESSION["round_counter"] < 2) {
                $_SESSION["round_counter"]++;
                echo '<label>Which dice do you want to roll again?</label><br>';
                foreach ($yatzy->dice as $dice) {
                    echo '<label for="' . $counter . '">Dice ' . $counter . '</label>';
                    echo '<input type="checkbox" id="' . $counter . '" name="dice' . $counter . '" value="' . $counter . '"><span style="padding-left: 95px;">';
                    $counter++;
                }
                echo '<br><br>';
                echo '<input type="submit" value="Throw dice!"><br><br>';
                echo '</form>';
            } else {
                foreach ($yatzy->get_dice_values() as $value) {
                    if ($value == $_SESSION["round"]) {
                        $_SESSION["points"] += $value;
                    }
                };
                $_SESSION["round_counter"] = 0;
                $_SESSION["round"]++;
                $_SESSION["points"] += $_SESSION["round_points"];
                echo '<br><br>';
                echo '<input type="submit" value="Next round!"><br><br>';
                echo '</form>';
            }

            if ($_SESSION["next_round"] === true) {
                foreach ($yatzy->get_dice_values() as $value) {
                    if ($value == $_SESSION["round"]) {
                        $_SESSION["round"] += $value;
                    }
                };
                $_SESSION["round_counter"] = 0;
                $_SESSION["round"]++;
            }
        }
        
    } else {
        $_SESSION["to_roll"] = [1, 2, 3, 4, 5];
        $_SESSION["points"] = 0;
        $_SESSION["round_points"] = 0;
        $_SESSION["round"] = 1;
        $_SESSION["round_counter"] = 0;
        $_SESSION["next_round"] = false;
        echo '<form method="post">';
        echo '<input type="submit" value="Start game!"><br><br>';
        echo '</form>';
        $_SESSION["yatzy"] = new YatzyGame;
    }
?>
