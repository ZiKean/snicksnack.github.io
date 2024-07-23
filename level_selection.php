<?php
// Database connection
$con = mysqli_connect("localhost", "root", "", "snicksnack");
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Check if user is logged in
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get logged-in user's username
$loggedInUsername = $_SESSION['username'];

// Query the result table to get progress for the logged-in user
$sqlProgress = "SELECT `Level_ID`, `star` FROM result WHERE Username = '$loggedInUsername'";
$resultProgress = mysqli_query($con, $sqlProgress);

// Create an associative array to store the progress for each level
$userProgress = array();
while ($rowProgress = mysqli_fetch_assoc($resultProgress)) {
    $userProgress[$rowProgress['Level_ID']] = $rowProgress['star'];
}

// Get column names from the level table
$sqlColumns = "SHOW COLUMNS FROM level";
$resultColumns = mysqli_query($con, $sqlColumns);

// Store column names in an array
$columns = array();
while ($rowColumns = mysqli_fetch_assoc($resultColumns)) {
    $columns[] = $rowColumns['Field'];
}

// Assuming the level ID column is the first column and the level name is the second column
$Level_ID = $columns[0];
$LevelName = $columns[1];

// Fetch level data from the database
$sqlLevels = "SELECT `Level_ID`, `LevelName` FROM level";
$resultLevels = mysqli_query($con, $sqlLevels);

// Create an array to store the level data
$levels = array();
$totalUserStars = array_sum($userProgress); // Calculate the total stars earned by the user
// Fetch the total number of levels from the database
$sqlTotalLevels = "SELECT COUNT(*) AS total_levels FROM level";
$resultTotalLevels = mysqli_query($con, $sqlTotalLevels);
$rowTotalLevels = mysqli_fetch_assoc($resultTotalLevels);
$totalLevels = $rowTotalLevels['total_levels'];

// Calculate the total possible stars based on the total number of levels
$totalPossibleStars = $totalLevels * 3;


if (mysqli_num_rows($resultLevels) > 0) {
    while ($rowLevels = mysqli_fetch_assoc($resultLevels)) {
        // Calculate stars and check if level is unlocked based on user progress
        $stars = isset($userProgress[$rowLevels[$Level_ID]]) ? $userProgress[$rowLevels[$Level_ID]] : 0;
        $unlocked = ($rowLevels[$Level_ID] == 1) || (isset($userProgress[$rowLevels[$Level_ID] - 1]) && $userProgress[$rowLevels[$Level_ID] - 1] == 3);

        $levels[] = array(
            "level_id" => $rowLevels[$Level_ID],
            "name" => $rowLevels[$LevelName],
            "stars" => $stars,
            "unlocked" => $unlocked
        );
    }
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Level Selection</title>
    <link rel="icon" href="Images/Apple.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;800&display=swap">
    <style>

body {
    background-image: url('Images/Background3.jpg');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    font-family: 'Kanit', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.progress {
    background-color: #ffdab9;
    border-radius: 10px;
    padding: 8px 22px;
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    align-items: center;
    border: 3px solid #000000;
    font-size: 33px;
}

.progress img {
    width: 30px;
    height: 30px;
    margin-right: 10px;
}

#level-buttons-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

/* Level button styles */
.level-button {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    margin: 19px;
    padding: 30px;
    border: 3px solid #000000;
    border-radius: 15px;
    background-color: blue;
    cursor: pointer;
}

.level-button.locked {
    opacity: 0.5;
    cursor: not-allowed;
}

.level-number {
    font-size: 36px;
    font-weight: bold;
    margin-bottom: 5px;
}

.star-rating {
    display: flex;
}

.star-rating img {
    width: 30px;
    height: 30px;
    margin: 2px;
}

/* Progress bar styles */
.progress {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.progress img {
    width: 30px;
    height: 30px;
    margin-right: 10px;
}

.progress span {
    font-weight: bold;
}

/* Pagination button styles */
.pagination {
    display: flex;
    justify-content: center;
    margin: 10px;
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

.arrow-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.next-button {
    display: none;
}

/* Back button styles */
.back-button {
    background-color: #ffdab9;
    border-radius: 10px;
    padding: 10px 20px;
    text-decoration: none;
    color: #000000;
    cursor: pointer;
    transition: background-color 0.3s;
    position: absolute;
    bottom: 10px;
    left: 10px;
    border: 3px solid #000000;
    font-size: 29px;
    font-weight: 800;
}

.back-button:hover {
    background-color: #ffd699;
}

.next-button{
    display: none;
}
</style>
</head>
<body>
<div class="progress">
        <img src="Images/star.png" alt="Star">
        <span id="progress-count">0</span>/<span id="total-stars"><?php echo $totalPossibleStars; ?></span>
    </div>

    <div class="level-container">
        <div class="level-buttons-row">
            <div class="pagination">
                <button id="prev-button" class="arrow-button" disabled>
                    <img src="Images/pre_arrow.png" alt="Previous">
                </button>
                <div id="level-buttons-container">
                    <div class="button-container">
                        <button class="button previous-button" id="previousButton">Previous</button>
                        <button class="button next-button" id="nextButton">Next</button>
                    </div>
                    <!-- Level buttons go here -->
                </div>
                <button id="next-button" class="arrow-button">
                    <img src="Images/next_arrow.png" alt="Next">
                </button>
            </div>
        </div>
    </div>

    <a href="dashboard_user.php" class="back-button">BACK</a>
    <script>
        // Get the level buttons container element
        const levelButtonsContainer = document.getElementById('level-buttons-container');
        const prevButton = document.getElementById('prev-button');
        const nextButton = document.getElementById('next-button');

        // Function to create a level button element
        function createLevelButton(level) {
            const levelButton = document.createElement('div');
            levelButton.classList.add('level-button');

            if (!level.unlocked) {
                levelButton.classList.add('locked');
            }

            const levelNumber = document.createElement('div');
            levelNumber.classList.add('level-number');
            levelNumber.textContent = level.level_id;

            const starRating = document.createElement('div');
            starRating.classList.add('star-rating');

            for (let i = 0; i < level.stars; i++) {
                const starImg = document.createElement('img');
                starImg.src = 'Images/star.png';
                starImg.alt = 'Star';
                starRating.appendChild(starImg);
            }

            for (let i = level.stars; i < 3; i++) {
                const starLockedImg = document.createElement('img');
                starLockedImg.src = 'Images/nostar.png';
                starLockedImg.alt = 'Star Locked';
                starRating.appendChild(starLockedImg);
            }

            levelButton.appendChild(levelNumber);
            levelButton.appendChild(starRating);

            // Add click event listener to the level button
            levelButton.addEventListener('click', function() {
                if (level.unlocked) {
                    // Navigate to the game.php page with the level_id as a query parameter
                    window.location.href = 'game.php?level_id=' + level.level_id;
                } else {
                    alert('You need to score 3 stars in the previous level to unlock this level.');
                }
            });

            return levelButton;
        }

        // Generate and append level buttons
        <?php
            if (!empty($levels)) {
                echo "const levels = " . json_encode($levels) . ";";
                echo "let currentPage = 0;";
                echo "const totalPages = Math.ceil(levels.length / 5);";
                echo "document.getElementById('progress-count').textContent = $totalUserStars;";
                echo "document.getElementById('total-stars').textContent = $totalPossibleStars;";
                echo "renderLevels();";

                echo "prevButton.addEventListener('click', () => {";
                echo "  if (currentPage > 0) {";
                echo "    currentPage--;";
                echo "    renderLevels();";
                echo "  }";
                echo "});";

                echo "nextButton.addEventListener('click', () => {";
                echo "  if (currentPage < totalPages - 1) {";
                echo "    currentPage++;";
                echo "    renderLevels();";
                echo "  }";
                echo "});";

                echo "function renderLevels() {";
                echo "  levelButtonsContainer.innerHTML = '';";
                echo "  const startIndex = currentPage * 5;";
                echo "  const endIndex = Math.min(startIndex + 5, levels.length);";
                echo "  for (let i = startIndex; i < endIndex; i++) {";
                echo "    const levelButton = createLevelButton(levels[i]);";
                echo "    levelButtonsContainer.appendChild(levelButton);";
                echo "  }";
                echo "  prevButton.disabled = currentPage === 0;";
                echo "  nextButton.disabled = currentPage === totalPages - 1;";
                echo "}";
            } else {
                echo "console.log('No levels found in the database.');";
            }

            ?>
        prevButton.addEventListener('click', () => {
            if (currentPage > 0) {
                currentPage--;
                renderLevels();
            }
        });

        nextButton.addEventListener('click', () => {
            if (currentPage < totalPages - 1) {
                currentPage++;
                renderLevels();
            }
        });

        function renderLevels() {
            levelButtonsContainer.innerHTML = '';
            const startIndex = currentPage * 5;
            const endIndex = Math.min(startIndex + 5, levels.length);
            for (let i = startIndex; i < endIndex; i++) {
                const levelButton = createLevelButton(levels[i]);
                levelButtonsContainer.appendChild(levelButton);
            }

            // Show/hide the appropriate buttons based on the current page
            if (currentPage === 0) {
                prevButton.style.display = 'none';
                nextButton.style.display = 'block';
            } else if (currentPage === totalPages - 1) {
                prevButton.style.display = 'block';
                nextButton.style.display = 'none';
            } else {
                prevButton.style.display = 'block';
                nextButton.style.display = 'block';
            }

            prevButton.disabled = currentPage === 0;
            nextButton.disabled = currentPage === totalPages - 1;
        }
    </script>
</body>
</html>