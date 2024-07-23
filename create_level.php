<?php
include("conn.php");
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
    <style>
        body {
            background-image: url('Images/Background3.jpg');
            font-family: 'Kanit', sans-serif;
            display: flex;
            align-items: center;
            flex-direction: column;
        }

        img {
            width: 500px;
            margin: 10px;
        }

        h1 {
            font-size: 40px;
            text-align: center;
            margin: 0px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .lvlname {
            width: 700px;
            padding: 20px;
            font-size: 20px;
            margin-bottom: 10px;
            border: 3px solid black;
            border-radius: 25px;
        }

        .submitbtn,
        .backbtn {
            margin-top: 10px;
            font-size: 20px;
            font-size: 32px;
            border: 3px solid black;
            border-radius: 25px;
            font-weight: 900;
            padding: 5px;
            transition: background-color 0.3s ease;
            background: #E5CEA0;
            width: 175px;
        }

        .submitbtn:hover,
        .backbtn:hover {
            background-color: #d9c08c;
        }
    </style>
</head>
<body>
    <h1><img src="Images/SnickSnack.png" alt="Logo"></h1>
    <h1>Enter the Level's Name</h1>
    <form method="post">
        <input type="text" name="LevelName" placeholder="Level Name" class="lvlname"><br>
        <input type="submit" name="submit" value="Submit" class="submitbtn">
    </form>
    <?php
    if (isset($_POST['submit'])) {
        $LevelName = $_POST['LevelName'];

        $sql = "INSERT INTO level (LevelName) VALUES ('$LevelName')";
        $result = mysqli_query($con, $sql);
        if ($result) {
            $LVLID = mysqli_insert_id($con);
            echo "Successfully Uploaded";
            echo '<script> window.location.href = "create_question.php?LVLID='.$LVLID.'"; </script>';
        } else {
            echo '<script>alert("Failed to Upload")</script>';
        }
    }
    ?>
    <form action="dashboard_admin.php">
        <input type="submit" value="Back" class="backbtn">
    </form>
</body>
</html>