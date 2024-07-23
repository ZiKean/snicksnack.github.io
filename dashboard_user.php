<?php
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['status'] = "Please log in to access this page.";
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnickSnack</title>
    <link rel="icon" href="Images/Apple.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;800&display=swap">
    <style>
        body {
            background-image: url('Images/Background2.jpg');
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

        .user-main-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: -100px;
        }

        .logo {
            margin-bottom: 30px;
            width: 968px;
            height: 657px;
            animation: fadeIn 1s forwards, shakeLogo 1s ease-in-out infinite alternate;
        }

        @keyframes shakeLogo {
            0% {
                transform: rotate(-1deg);
            }
            50% {
                transform: rotate(1deg);
            }
            100% {
                transform: rotate(-1deg);
            }
        }


        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }


        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            padding: 0 150px;
        }

        .button {
            display: inline-block;
            padding: 10px;
            text-decoration: none;
            font-weight: 800;
            border-radius: 25px;
            border: 3px solid #000000;
            transition: background-color 0.3s ease, transform 0.3s ease;
            font-size: 40px;
        }

        .button:hover {
            background-color: #d9d9d9;
            color: #000000;
            transform: scale(1.05);
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        }



        .logout-button {
            background-color: #e5cea0;
            color: #000000;
            width: 200px;
            text-align: center;
            margin-top: 150px;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        }

        .logout-button:hover {
            background-color: #ff4d4d;
            box-shadow: 0px 5px 10px rgba(255, 77, 77, 0.3);
            transform: scale(1.05); 
            animation: shake 0.4s ease-in-out infinite;
        }

        @keyframes shake {
            0% {
                transform: rotate(-3deg);
            }
            50% {
                transform: rotate(3deg);
            }
            100% {
                transform: rotate(-3deg);
            }
        }

        .start-button {
            background-color: #36c91c;
            color: #000000;
            width: 325px;
            text-align: center;
            margin: 10px 0;
            animation: zoomInOut 1.5s ease-in-out infinite alternate;
            box-shadow: 0px 5px 15px rgba(0, 255, 0, 0.3);
        }

        .start-button:hover {
            background-color: #75db5c;
            animation: none;
            transform: scale(1.1);
        }


        @keyframes zoomInOut {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .profile-button,
        .leaderboard-button {
            background-color: #ffa500;
            color: #000000;
            width: 325px;
            text-align: center;
            margin: 10px 0;
        }

        .profile-button:hover,
        .leaderboard-button:hover {
            background-color: #ff8c00;
        }

        .main-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: -150px; 
            margin-left: 550px;             
        }
    </style>
</head>
<body>
    <div class="user-main-page">
        <div class="logo fade-in">
            <img src="Images/SnickSnack.png" alt="SnickSnack logo" style="width: 100%; height: 100%;">
        </div>
        <div class="button-container">
            <div class="main-buttons">
                <a href="level_selection.php" class="button start-button">START</a>
                <a href="profile.php" class="button profile-button">PROFILE</a>
                <a href="leaderboard.php" class="button leaderboard-button">LEADERBOARD</a>
            </div>
            <a href="logout.php" class="button logout-button">LOGOUT</a>
        </div>
    </div>
</body>
</html>