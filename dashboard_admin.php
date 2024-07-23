<?php
session_start();


// Check if user is logged in
if (!isset($_SESSION['email'])) {
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

    .admin-main-page {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: -120px;
    }

    .logo {
        width: 968px;
        height: 657px;
    }

    .button-container {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        margin-top: -50px;
    }

    .button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #e5cea0;
        color: #000000;
        text-decoration: none;
        font-weight: 800;
        border-radius: 20px;
        border: 3px solid #000000;
        transition: background-color 0.3s ease;
        font-size: 40px;
        margin-top: 20px; /* Adjust the vertical space between buttons */
        width: 275px;
        text-align: center;
    }

    .button:hover {
        background-color: #d9c08c;
    }
    </style>
</head>
<body>
    <div class="admin-main-page">
        <div class="logo">
            <img src="Images/SnickSnack.png" alt="SnickSnack logo" style="width: 100%; height: 100%;">
        </div>
        <div class="button-container">
            <a href="create_level.php" class="button create-level-button">CREATE LEVEL</a>
            <a href="edit_level.php" class="button edit-level-button">EDIT LEVEL</a>
            <a href="profile_manage.php" class="button manage-user-button">MANAGE USER</a>
            <a href="logout.php" class="button">LOGOUT</a>
            
            <?php
            if (isset($_SESSION['user_id'])) {
                echo '<a href="profile.php" class="button">View Profile</a>';
            }
            ?>
        </div>
    </div>
</body>
</html>




