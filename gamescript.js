// Constants
const board = document.getElementById("gameboard");
const instructionText = document.getElementById("instruction_text");
const score = document.getElementById('score');
const livesDisplay = document.getElementById('lives');
const sounds = {
    eat: document.getElementById('eatSound'),
    wrongFood: document.getElementById('wrongFoodSound'),
    gameOver: document.getElementById('gameOverSound'),
    backgroundMusic: document.getElementById('backgroundMusic'),
    movement: document.getElementById('movementSound'),
    pause: document.getElementById('pauseSound'),
    win: document.getElementById('winSound')
};
const fruits = ['Apple', 'Banana', 'Coconut'];
let questions = [];
const countdownScreen = document.getElementById("countdownScreen");
const countdownText = document.getElementById("countdownText");

// Game Variables
const gridSize = 20;
let snake, optionCorrect, optionWrong1, optionWrong2, direction, gameInterval, gameSpeedDelay, gameStarted, lives, currentScore, currentQuestionIndex, countdownActive;

// Initialize the game
function initializeGame(fetchedQuestions) {
    if (!fetchedQuestions || !Array.isArray(fetchedQuestions)) {
        console.error("Fetched questions are invalid:", fetchedQuestions);
        return;
    }

    questions = fetchedQuestions; // Assign the fetched questions to the global variable

    // Shuffle the questions array
    questions.sort(() => Math.random() - 0.5);

    snake = [
        { x: 10, y: 10 },
        { x: 10, y: 9 },
        { x: 10, y: 8 },
        { x: 10, y: 7 },
        { x: 10, y: 6 },
        { x: 10, y: 5 },
        { x: 10, y: 4 }
    ];

    // Reset game variables
    direction = "down";
    gameSpeedDelay = 200;
    gameStarted = false;
    lives = 3;
    currentScore = 0;
    currentQuestionIndex = 0;

    // Generate new correct and wrong options
    let selectedFruits = [];
    optionCorrect = generateUniquePosition();
    optionWrong1 = generateUniquePosition();
    optionWrong2 = generateUniquePosition();

    // Ensure three unique fruits are generated
    while (optionWrong2.x === optionWrong1.x && optionWrong2.y === optionWrong1.y) {
        optionWrong2 = generateUniquePosition();
    }

    assignFruits(selectedFruits);

    draw();
    updateScore();
    updateLives();
    instructionText.style.display = 'block';
    sounds.backgroundMusic.loop = true;
    sounds.backgroundMusic.volume = 1;
    sounds.backgroundMusic.play();
}

function draw() {
    clearBoard();
    drawSnake();

    drawOption(optionCorrect, 'option-correct');
    drawOption(optionWrong1, 'option-wrong');
    drawOption(optionWrong2, 'option-wrong');
    updateScore();
    updateLives();

    const questionContainer = document.querySelector('.question-container');
    const optionsContainer = document.querySelector('.options-container');

    // Clear previous question and options
    questionContainer.innerHTML = '';
    optionsContainer.innerHTML = '';

    // Display current question
    if (currentQuestionIndex < questions.length) {
        const currentQuestion = questions[currentQuestionIndex];
        const questionElement = document.createElement('h2');
        questionElement.textContent = currentQuestion.question;
        questionContainer.appendChild(questionElement);

        // Display answer options
        currentQuestion.options.forEach((option, index) => {
            const optionElement = document.createElement('div');
            const optionText = document.createElement('div');
            const optionImage = document.createElement('img');

            optionText.textContent = option;
            optionText.classList.add('option-text');
            optionImage.src = `Images/${fruits[index]}.png`;
            optionImage.classList.add('option-image');

            optionElement.classList.add('option');
            optionElement.dataset.fruit = fruits[index];

            optionElement.appendChild(optionImage);
            optionElement.appendChild(optionText);

            optionsContainer.appendChild(optionElement);
            console.log(`Option: ${option}, Fruit Image: Images/${fruits[index]}.png`);
        });
    } else {
        questionContainer.textContent = "No more questions. Game over!";
    }
}

function clearBoard() {
    board.innerHTML = "";
}

function drawSnake() {
    snake.forEach((segment, index) => {
        const snakeElement = createGameElement('div', 'snake');
        setPosition(snakeElement, segment);
        if (index === 0) {
            snakeElement.classList.add('snake-head');
        }
        board.appendChild(snakeElement);
    });
}

function createGameElement(tag, className) {
    const element = document.createElement(tag);
    element.className = className;
    return element;
}

function setPosition(element, position) {
    element.style.gridColumn = position.x;
    element.style.gridRow = position.y;
}

function drawOption(option, className) {
    const optionElement = createGameElement('div', className);
    setPosition(optionElement, option);
    optionElement.style.width = '20px';
    optionElement.style.height = '20px';
    optionElement.style.backgroundImage = `url('Images/${option.fruit}.png')`;
    optionElement.style.backgroundSize = 'cover';
    optionElement.style.borderRadius = '50%';
    board.appendChild(optionElement);
}

function isSnakeSegment(position) {
    return snake.some(segment => segment.x === position.x && segment.y === position.y);
}

function generateUniquePosition() {
    let position;
    do {
        position = {
            x: Math.floor(Math.random() * gridSize) + 1,
            y: Math.floor(Math.random() * gridSize) + 1
        };
    } while (isSnakeSegment(position) || positionOverlapsOptions(position));
    return position;
}

function positionOverlapsOptions(position) {
    return (position.x === optionCorrect?.x && position.y === optionCorrect?.y) ||
           (position.x === optionWrong1?.x && position.y === optionWrong1?.y) ||
           (position.x === optionWrong2?.x && position.y === optionWrong2?.y);
}

function assignFruits(selectedFruits) {
    const question = questions[currentQuestionIndex];
    const correctOption = question.correct;
    const correctIndex = ['A', 'B', 'C'].indexOf(correctOption);  // Correct option index
    const correctFruit = fruits[correctIndex];  // Find the corresponding fruit
    selectedFruits.push(correctFruit);  // Add the correct fruit to the selected fruits array
    optionCorrect.fruit = correctFruit;  // Assign the correct fruit

    // Generate wrong options
    const remainingFruits = fruits.filter(f => !selectedFruits.includes(f));
    optionWrong1.fruit = remainingFruits[0];
    optionWrong2.fruit = remainingFruits[1];
}

function move() {
    const head = { ...snake[0] }; 
    switch (direction) {
      case "up":
        head.y--;
        break;
      case "right":
        head.x++;
        break;
      case "left":
        head.x--;
        break;
      case "down":
        head.y++;
        break;
    }
  
    if (head.x === optionCorrect.x && head.y === optionCorrect.y) {
      handleCorrectOptionCollision();
    } else if (head.x === optionWrong1.x && head.y === optionWrong1.y) {
      handleWrongOptionCollision(optionWrong1.fruit);
    } else if (head.x === optionWrong2.x && head.y === optionWrong2.y) {
      handleWrongOptionCollision(optionWrong2.fruit);
    } else {
      // Check for wall and self collision here
      if (head.x < 1 || head.x > gridSize || head.y < 1 || head.y > gridSize) {
        gameOver();
        return;
      }
      for (let i = 1; i < snake.length; i++) {
        if (head.x === snake[i].x && head.y === snake[i].y) {
          gameOver();
          return;
        }
      }
      snake.unshift(head);
      snake.pop();
    }
  }

function handleCorrectOptionCollision() {
    currentScore += 10;
    sounds.eat.currentTime = 0;
    sounds.eat.play();

    currentQuestionIndex++;
    if (currentQuestionIndex < questions.length) {
        let selectedFruits = [];
        optionCorrect = generateUniquePosition();
        optionWrong1 = generateUniquePosition();
        optionWrong2 = generateUniquePosition();

        // Ensure three unique fruits are generated
        while (optionWrong2.x === optionWrong1.x && optionWrong2.y === optionWrong1.y) {
            optionWrong2 = generateUniquePosition();
        }
        assignFruits(selectedFruits);
    } else {
        gameOver();
        return;
    }
    draw();
}

function handleWrongOptionCollision(fruit) {
    lives--;
    if (lives === 0) {
        gameOver();
    } else {
        currentQuestionIndex++;
        if (currentQuestionIndex < questions.length) {
            let selectedFruits = [];
            optionCorrect = generateUniquePosition();
            optionWrong1 = generateUniquePosition();
            optionWrong2 = generateUniquePosition();

            // Ensure three unique fruits are generated
            while (optionWrong2.x === optionWrong1.x && optionWrong2.y === optionWrong1.y) {
                optionWrong2 = generateUniquePosition();
            }
            assignFruits(selectedFruits);
        } else {
            gameOver();
            return;
        }
        draw();
        updateLives();
        sounds.wrongFood.currentTime = 0;
        sounds.wrongFood.play();
    }
}

function startGame() {
    clearInterval(gameInterval);
    lives = 3;
    currentScore = 0;
    gameStarted = true;
    instructionText.style.display = 'none';
    document.body.classList.add('gameStarted');

    gameInterval = setInterval(() => {
        move();
        checkCollision();
        draw();
    }, gameSpeedDelay);
    sounds.backgroundMusic.play();
}

// Countdown function
function showCountdown(callback) {
    let countdown = 3;
    countdownScreen.style.display = 'flex';
    countdownText.textContent = 'Ready?';
    countdownActive = true;

    const countdownInterval = setInterval(() => {
        countdownText.textContent = countdown;
        countdown--;
        if (countdown < 0) {
            clearInterval(countdownInterval);
            countdownScreen.style.display = 'none';
            countdownActive = false; // Reset countdownActive flag
            callback(); 
          }
        }, 1000);
}

function checkCollision() {
    const head = snake[0];
    if (head.x < 1 || head.x > gridSize || head.y < 1 || head.y > gridSize) {
        gameOver();
    }
    for (let i = 1; i < snake.length; i++) {
        if (head.x === snake[i].x && head.y === snake[i].y) {
            gameOver();
        }
    }
}

function resetGame() {
    stopGame();
    document.body.classList.remove('gameStarted');
    initializeGame(questions);
    updateScore();
    updateLives();
    draw();
    showCountdown(startGameAfterCountdown);
}

function updateScore() {
    score.textContent = currentScore.toString().padStart(1, '0');
}

function updateLives() {
    const hearts = document.querySelectorAll('.heart');
    for (let i = 0; i < hearts.length; i++) {
        if (i < lives) {
            hearts[i].src = "Images/adahp.png";
            hearts[i].alt = `Heart ${i + 1}`;
        } else {
            hearts[i].src = "Images/tadahp.png";
            hearts[i].alt = "Empty Heart";
        }
    }
}

function stopGame() {
    clearInterval(gameInterval);
    gameStarted = false;
    instructionText.style.display = 'block';
    sounds.backgroundMusic.pause();
}

function gameOver() {
    stopGame();
    let gameOverMessage;
    const head = snake[0];
    if (head.x < 1 || head.x > gridSize || head.y < 1 || head.y > gridSize) {
        gameOverMessage = "You hit the wall!";
    } else if (lives === 0) {
        gameOverMessage = "You lost all your lives!";
    } else if (currentScore >= 70) {
        gameOverMessage = "Great job! You scored " + currentScore + " points!";
        sounds.win.currentTime = 0;
        sounds.win.play();
    } else {
        gameOverMessage = "You hit yourself!";
    }

    if (currentScore < 70) {
        sounds.gameOver.currentTime = 0;
        sounds.gameOver.play();
    }

    setTimeout(function() {
        if (currentScore >= 70) {
            alert("Great job! You scored " + currentScore + " points!");
        } else {
            alert("Game Over! " + gameOverMessage + " Your score: " + currentScore);
        }

        // Redirect to result.php with the game results
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'result.php';

        const usernameField = document.createElement('input');
        usernameField.type = 'hidden';
        usernameField.name = 'username';
        usernameField.value = document.getElementById('username').value;
        form.appendChild(usernameField);

        const levelIdField = document.createElement('input');
        levelIdField.type = 'hidden';
        levelIdField.name = 'level_id';
        levelIdField.value = levelId;
        form.appendChild(levelIdField);

        const scoreField = document.createElement('input');
        scoreField.type = 'hidden';
        scoreField.name = 'score';
        scoreField.value = currentScore;
        form.appendChild(scoreField);

        const totalQuestionsField = document.createElement('input');
        totalQuestionsField.type = 'hidden';
        totalQuestionsField.name = 'totalQuestions';
        totalQuestionsField.value = questions.length;
        form.appendChild(totalQuestionsField);

        document.body.appendChild(form);
        form.submit();
    }, currentScore >= 70 ? sounds.win.duration * 1000 : sounds.gameOver.duration * 1000);
}

function pauseGame() {
    if (countdownActive) return;

    if (gameStarted) {
        clearInterval(gameInterval);
        gameStarted = false;
        instructionText.style.display = 'block';
        sounds.backgroundMusic.pause();
        const pauseMenu = document.getElementById("pauseMenu");
        pauseMenu.style.display = "flex";
        sounds.pause.currentTime = 0;
        sounds.pause.play();
    }
}

function resumeGame() {
    const pauseMenu = document.getElementById("pauseMenu");
    pauseMenu.style.display = "none";
    gameStarted = true;
    instructionText.style.display = 'none';
    document.body.classList.add('gameStarted');
    gameInterval = setInterval(() => {
        move();
        checkCollision();
        draw();
    }, gameSpeedDelay);
    sounds.backgroundMusic.play();
}

function restartGame() {
    const pauseMenu = document.getElementById("pauseMenu");
    pauseMenu.style.display = "none";
    const confirmRestart = confirm("Are you sure you want to restart the game?");
    if (confirmRestart) {
        stopGame();
        initializeGame(questions); // Ensure questions are re-initialized
        updateScore();
        updateLives();
        draw();
        showCountdown(startGameAfterCountdown); // Use countdown before restarting the game
        instructionText.style.display = 'none';
    } else {
        pauseMenu.style.display = "flex";
    }
}

function backToLevelSelect() {
    const confirmation = confirm("Are you sure you want to go back to the level selection? Your progress will be lost.");
    if (confirmation) {
        window.location.href = 'level_selection.php';
    }
}

function togglePauseMenu() {
    if (countdownActive) return;  // Prevent showing the pause menu during countdown

    const pauseMenu = document.getElementById("pauseMenu");
    if (pauseMenu.style.display === "none" || pauseMenu.style.display === "") {
        pauseMenu.style.display = "flex";
    } else {
        pauseMenu.style.display = "none";
    }
}

// Function to start the game after the countdown ends
function startGameAfterCountdown() {
    startGame();
}

// Event listener for the start button
document.addEventListener('DOMContentLoaded', () => {
    const startPopup = document.getElementById('startPopup');
    const startGameButton = document.getElementById('startGameButton');

    startPopup.style.display = 'flex';

    startGameButton.addEventListener('click', () => {
        startPopup.style.display = 'none'; 
        const countdownScreen = document.getElementById('countdownScreen');
        countdownScreen.style.display = 'flex'; 
        showCountdown(() => {
            // Function to start the game after the countdown ends
            startGameAfterCountdown();
            countdownScreen.style.display = 'none'; 
        });
    });
});
