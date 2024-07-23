<?php
include 'conn.php';
session_start();

if (!isset($_SESSION['email'])) {
    $_SESSION['status'] = "Please log in to access this page.";
    header("Location: login.php");
    exit();
}

$levelId = isset($_GET['levelId']) ? (int)$_GET['levelId'] : 1;
$levelName = '';
$query = "SELECT levelname FROM Level WHERE Level_id = $levelId";
$result = mysqli_query($con, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $levelName = $row['levelname'];
} else {
    echo "Error fetching level name: " . mysqli_error($conn);
}

$nextLevelCheck = false;
$queryNext = "SELECT COUNT(*) as total FROM Level WHERE Level_id > $levelId";
$resultNext = mysqli_query($con, $queryNext);
if ($resultNext) {
    $rowNext = mysqli_fetch_assoc($resultNext);
    $nextLevelCheck = $rowNext['total'] > 0;
} else {
    echo "Error checking next level: " . mysqli_error($con);
}

$queryTop3Players = "SELECT DISTINCT Username FROM result WHERE Level_id = $levelId ORDER BY Star DESC LIMIT 3";
$resultTop3Players = mysqli_query($con, $queryTop3Players);
$top3Players = [];
if ($resultTop3Players) {
    while ($rowTopPlayer = mysqli_fetch_assoc($resultTop3Players)) {
        $top3Players[] = $rowTopPlayer['Username'];
    }
} else {
    echo "Error fetching top users: " . mysqli_error($con);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>
    <link rel="icon" href="Images/Apple.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;800&display=swap">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: 'Kanit', sans-serif;
        }
        
        .container {
            background: #75DB5C;
            font-size: 25px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 25px;
            border: 4px solid black;
            width: 600px;
            height: 450px;
            padding: 20px;
        }

        .logo {
            display: flex;
            width: 200px;
            height: 150px;
        }


        .LeaderboardLabel {
            background: #E5CEA0;
            border-radius: 25px;
            border: 3px solid black;
            text-align: center;
            font-size: 14px;
            padding-left: 200px;
            padding-right: 200px;
        }

        .button {
            text-decoration: none;
            border-radius: 25px;
            border: 2px solid #000000;
            transition: background-color 0.3s ease;
            font-size: 20px;
            font-weight: 600;
        }

        .skin-button,
        .next-button {
            background-color: #e5cea0;
            color: #000000;
            width: 100px;
            text-align: center;
        }

        .skin-button:hover,
        .next-button:hover {
            background-color: #d9c08c;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
        }

        p {
            margin: 20px;
            font-size: 30px;
            font-weight: 600;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            font-size: 30px;
        }

        li {
            display: flex;
            align-items: center;
            margin: 20px;
        }

        .medal {
            margin-right: 5px;
            width: 40px;
            height: 40px;
        }

        .backbutton {
            background-color: #e5cea0;
            text-decoration: none;
            border-radius: 25px;
            border: 4px solid #000000;
            transition: background-color 0.3s ease;
            font-size: 30px;
            font-weight: 600;
            padding: 10px 30px;
        }

        .backbutton:hover {
            background-color: #d9c08c;
        }

        .arrow-button {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .arrow-button img {
            width: 90px; /* Adjust the width as per your requirements */
            height: 60px; /* Adjust the height as per your requirements */
        }
    </style>
</head> 
<body style="background-image: url('Images/Background1.jpg'); background-size: cover;">
    <div class="logo">
        <img src="Images/SnickSnack.png" alt="SnickSnake_Logo" style="width: 100%; height: 100%;">
    </div>
    <div class="LeaderboardLabel">
        <h1>Leaderboard</h1>
    </div>
    <br>
    <div class="container">
        <div class="button-container">
            <!-- <button class="button previous-button" id="previousButton">Previous</button> -->
            <button id="previousButton" class="arrow-button">
                <img src="Images/pre_arrow.png" alt="Previous">
            </button>
            <button id="nextButton" class="arrow-button">
                <img src="Images/next_arrow.png" alt="Next">
            </button>
            </div>
        <p>Level <?php echo htmlspecialchars($levelId); ?></p>
        <p><?php echo htmlspecialchars($levelName); ?></p>
        <ul>
            <?php 
            $positions = ['Images/Gold.png', 'Images/Silver.png', 'Images/Bronze.png'];
            foreach ($top3Players as $index => $player): 
                $positionImage = isset($positions[$index]) ? $positions[$index] : ''; 
            ?>
                <li>
                    <img src="<?php echo $positionImage; ?>" alt="<?php echo $positionImage; ?>" class="medal">
                    <?php echo htmlspecialchars($player); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <br>
    <div>
        <button class="backbutton" id="backbutton">Back</button>
    </div>
<script>
    <?php if ($levelId > 1): ?>
        document.getElementById("previousButton").addEventListener("click", function() {
            window.location.href = "Leaderboard.php?levelId=<?php echo $levelId - 1; ?>";
        });
    <?php else: ?>
        document.getElementById("previousButton").style.visibility = "hidden";
    <?php endif; ?>

    <?php if ($nextLevelCheck): ?>
        document.getElementById("nextButton").addEventListener("click", function() {
            window.location.href = "Leaderboard.php?levelId=<?php echo $levelId + 1; ?>";
        });
    <?php else: ?>
        document.getElementById("nextButton").style.visibility = "hidden";
    <?php endif; ?>

    document.getElementById("backbutton").addEventListener("click", function() {
        window.location.href = "dashboard_user.php";
    });
</script>
</div>
</body>
</html>