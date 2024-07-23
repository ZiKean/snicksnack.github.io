<?php
include('conn.php');
session_start();

if (!isset($_SESSION['email'])) {
    $_SESSION['status'] = "Please log in to access this page.";
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

function getUsernameByEmail($email, $con) {
    $stmt = $con->prepare("SELECT Username FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
    return $username;
}

$username = getUsernameByEmail($email, $con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnickSnack</title>
    <link rel="icon" href="Images/Apple.png">
    <link rel="stylesheet" href="gamestyle.css">
</head>
<body>
    <input type="hidden" id="username" value="<?php echo htmlspecialchars($username); ?>">
    <div class="content">
        <div class="scores">
            <h1 id="score">00</h1>
        </div>

        <div id="heart-container">
            <img class="heart" src="Images/adahp.png" alt="Heart 1">
            <img class="heart" src="Images/adahp.png" alt="Heart 2">
            <img class="heart" src="Images/adahp.png" alt="Heart 3">
        </div>
        
        <div class="gameborder1">
            <div class="gameborder2">
                <div class="gameborder3">
                    <div id="gameboard"> </div>
                </div>
            </div>
        </div>
        
        <div id="startPopup" class="popup-container">
            <div class="popup-content">
                <h2>Welcome to the Game!</h2>
                <p>Before you start, here are some instructions:</p>
                <ul>
                    <li>Use WASD keys or the Arrow keys to move the snake.</li>
                    <li>Answer questions by controlling your snake to eat the fruits!</li>
                    <li>Avoid answering incorrectly or you will lose a life.</li>
                    <li>If you lose all your lives or hit the wall, the game is over.</li>
                    <li>Try to achieve the highest score possible!</li>
                </ul>
                <p>Press the button below when you are ready to start the game. ✨</p>
                <button id="startGameButton"></button>
            </div>
        </div>
      
        <img id="pauseButton" onclick="pauseGame()" src="Images/pause.png" alt="Pause Button">

        <div id="pauseMenu" class="pause-menu">
            <div class="content-container">
                <h2>PAUSE</h2>
                <div class="button-row">
                    <div class="button-wrapper">
                        <img src="Images/resume.png" alt="Resume Button" onclick="resumeGame()">
                        <span>Resume</span>
                    </div>
                    <div class="button-wrapper">
                        <img src="Images/restart.png" alt="Restart Button" onclick="restartGame()">
                        <span>Restart</span>
                    </div>
                    <div class="button-wrapper">
                        <img src="Images/menu.png" alt="Back to Level Select Button" onclick="backToLevelSelect()">
                        <span>Menu</span>
                    </div>
                </div>
            </div>
        </div>
      
        <!-- Quiz container -->
        <div class="quiz-container">
            <!-- Question container -->
            <div class="question-container">
                <h2></h2>
            </div>

            <!-- Options container -->
            <div class="options-container">
                <!-- Apple option -->
                <div class="option">
                    <img class="option-image" src="Images/Apple.png" alt="Apple">
                    <div class="option-text">Apple</div>
                </div>

                <!-- Banana option -->
                <div class="option">
                    <img class="option-image" src="Images/Banana.png" alt="Banana">
                    <div class="option-text">Banana</div>
                </div>

                <!-- Coconut option -->
                <div class="option">
                    <img class="option-image" src="Images/Coconut.png" alt="Coconut">
                    <div class="option-text">Coconut</div>
                </div>
            </div>
        </div>

        <!-- Countdown screen -->
        <div id="countdownScreen" class="countdown-screen">
            <h2 id="countdownText">Ready?</h2>
        </div>

        <div id="instruction_text" style="display: none;"></div>

        <!-- Include the slider background -->
        <iframe src="slider.html" style="width:100%; height: 1200px; border:none;"></iframe>

        <audio id="movementSound" src="SE/movementpop-se.mp3"></audio>
        <audio id="eatSound" src="SE/eating-se.mp3"></audio>
        <audio id="wrongFoodSound" src="SE/wronganswer-se.mp3"></audio>
        <audio id="gameOverSound" src="SE/gameover-se4.MP3"></audio>
        <audio id="backgroundMusic" src="SE/AGST - Reality (Royalty Free Music).mp3"></audio>
        <audio id="pauseSound" src="SE/pause.mp3"></audio>
        <audio id="winSound" src="SE/Winning.MP3"></audio>

        <script>
            // Get the level ID from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const levelId = urlParams.get('level_id');

            // Fetch questions and initialize game
            document.addEventListener('DOMContentLoaded', async () => {
                const levelId = urlParams.get('level_id');
                if (!levelId) {
                    alert('No level ID provided.');
                    return;
                }

                console.log('Fetching questions for level ID:', levelId);  // Debugging
                const questions = await fetchQuestions(levelId);

                if (questions.length > 0) {
                    console.log('Questions fetched successfully:', questions);  // Debugging
                    initializeGame(questions);  // Pass questions to initializeGame
                } else {
                    alert('No questions available for this level.');
                }
            });

            // Function to fetch questions
            async function fetchQuestions(levelId) {
                try {
                    const response = await fetch(`game_fetch_questions.php?level_id=${levelId}`);
                    const responseText = await response.text();
                    console.log('Raw response:', responseText);  // Debugging

                    // Remove any non-JSON text from the response if necessary
                    const jsonStart = responseText.indexOf('[');
                    if (jsonStart !== -1) {
                        const jsonString = responseText.substring(jsonStart);
                        const questions = JSON.parse(jsonString);
                        console.log('Questions:', questions);  // Debugging
                        return questions;
                    } else {
                        throw new Error('No JSON data found');
                    }
                } catch (error) {
                    console.error('Error fetching questions:', error);
                    return [];
                }
            }
        </script>

        <script src="gamescript.js"></script>
        <script src="eventHandlers.js"></script>
    </div>
</body>
</html>
