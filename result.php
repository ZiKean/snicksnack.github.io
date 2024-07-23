<?php
include 'conn.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$username = isset($_POST['username']) ? $_POST['username'] : 'guest';
$level_id = isset($_POST['level_id']) ? intval($_POST['level_id']) : 0;
$score = isset($_POST['score']) ? intval($_POST['score']) : 0;
$totalQuestions = isset($_POST['totalQuestions']) ? intval($_POST['totalQuestions']) : 10;
$stars = 0;

// Calculate stars based on score
if ($score == 100) {
    $stars = 3;
} elseif ($score >= 80) {
    $stars = 2;
} elseif ($score >= 70) {
    $stars = 1;
} else {
    $stars = 0;
}

// Save the result to the database
if ($level_id > 0 && $username) {
    $stmt = $con->prepare("INSERT INTO result (Username, Level_ID, Score, TotalQuestions, star) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param('siiii', $username, $level_id, $score, $totalQuestions, $stars);
    $stmt->execute();
    $stmt->close();
}

$con->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Result</title>
    <link rel="icon" href="Images/Apple.png">
    <link rel="stylesheet" href="resultstyle.css">
</head>
<body>
    <div class="slider-background">
        <div class="text-background">
            <div class="text-background-slider">
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
            </div>
        </div>

        <div class="text-background">
            <div class="text-background-slider-reversed">
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
            </div>
        </div>

        <div class="text-background">
            <div class="text-background-slider">
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
            </div>
        </div>

        <div class="text-background">
            <div class="text-background-slider-reversed">
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
            </div>
        </div>

        <div class="text-background">
            <div class="text-background-slider">
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
                <span>SNICKSNACK</span>
            </div>
        </div>
    </div>


    <div class="result-container">
        <h1>Game Over</h1>
        <p>Your score: <?php echo $score; ?></p>
        <div class="stars">
        <div class="spotlight"></div>
    <?php
        if ($score >= 70 && $score < 80) {
            echo '<img src="Images/star.png" alt="Star">';
            echo '<img src="Images/nostar.png" alt="No Star">';
            echo '<img src="Images/nostar.png" alt="No Star">';
        } elseif ($score >= 80 && $score < 90) {
            echo '<img src="Images/star.png" alt="Star">';
            echo '<img src="Images/star.png" alt="Star">';
            echo '<img src="Images/nostar.png" alt="No Star">';
        } elseif ($score >= 90 && $score < 100) {
            echo '<img src="Images/star.png" alt="Star">';
            echo '<img src="Images/star.png" alt="Star">';
            echo '<img src="Images/star.png" alt="Star">';
        } elseif ($score == 100) {
            echo '<img src="Images/star.png" alt="Star">';
            echo '<img src="Images/star.png" alt="Star">';
            echo '<img src="Images/star.png" alt="Star">';
        } else {
            for ($i = 0; $i < 3; $i++) {
                echo '<img src="Images/nostar.png" alt="No Star">';
            }
        }
        ?>
    </div>

        <p>You earned <?php echo $stars; ?> star<?php echo $stars != 1 ? 's' : ''; ?>!</p>
        <div class="button-container">
            <button class="retry-btn" onclick="window.location.href='game.php?level_id=<?php echo $level_id; ?>'">Play Again</button>
            <button class="level-select-btn" onclick="window.location.href='level_selection.php'">Level Selection</button>
        </div>
    </div>
</body>

</html>
