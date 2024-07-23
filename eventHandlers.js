// eventHandlers.js
document.addEventListener('keydown', handleKeypress);

function handleKeypress(event) {
    if (!gameStarted && event.code === "Space") {
        startGame();
    } else {
        let newDirection = direction;
        switch (event.key) {
            case "Escape":
                if (gameStarted) {
                    pauseGame();
                } else {
                    resumeGame();
                }
                break;
            case "ArrowUp":
            case "w":
                if (direction !== "down") {
                    newDirection = "up";
                }
                break;
            case "ArrowDown":
            case "s":
                if (direction !== "up") {
                    newDirection = "down";
                }
                break;
            case "ArrowLeft":
            case "a":
                if (direction !== "right") {
                    newDirection = "left";
                }
                break;
            case "ArrowRight":
            case "d":
                if (direction !== "left") {
                    newDirection = "right";
                }
                break;
        }
        if (newDirection !== direction) {
            direction = newDirection;
            playMovementSound();
        }
    }
}

function playMovementSound() {
    const movementSound = document.getElementById('movementSound');
    movementSound.currentTime = 0;
    movementSound.play();
}
