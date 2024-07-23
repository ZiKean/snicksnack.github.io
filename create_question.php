<?php
    include("conn.php");
    session_start();


    // Check if user is logged in
    if (!isset($_SESSION['email'])) {
        $_SESSION['status'] = "Please log in to access this page.";
        header("Location: login.php");
        exit();
    }

    if(isset($_POST['submit']))
    {
        $question = $_POST['question'];
        $option_A = $_POST['option_A'];
        $option_B = $_POST['option_B'];
        $option_C = $_POST['option_C'];
        $answer = $_POST['answer'];

        $sql_count = "SELECT COUNT(*) AS question_count FROM question WHERE Level_ID = $_GET[LVLID]";
        $result_count = mysqli_query($con, $sql_count);
        $row_count = mysqli_fetch_assoc($result_count);
        $question_count = $row_count['question_count'];

        if ($question_count >= 10) {
            echo '<script>alert("You have reached the limit of 10 questions.")</script>';
        } else {
            $sql = "INSERT INTO question (Level_ID, Question, option_A, option_B, option_C, answer) VALUES ($_GET[LVLID],'$question', '$option_A', '$option_B', '$option_C', '$answer')";
            $result = mysqli_query($con,$sql);
            if($result)
            {
                //echo '<script>alert("Successfully Uploaded")</script>'; (Annoying when testing)
            }
            else
            {
                echo "Failed to Upload";
            }
        }
    }

    $LVLID=$_GET['LVLID'];
    $sql1="SELECT * FROM level WHERE Level_ID=$LVLID";
    $result1=mysqli_query($con,$sql1);
    $result1=mysqli_fetch_assoc($result1);
    $result1=$result1['LevelName'];

    $sql_count = "SELECT COUNT(*) AS question_count FROM question WHERE Level_ID = $_GET[LVLID]";
    $result_count = mysqli_query($con, $sql_count);
    $row_count = mysqli_fetch_assoc($result_count);
    $question_count = $row_count['question_count'];
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
            justify-content: center;
            flex-direction: column;
            height: 100%;
            margin-left: 350px;
            font-size: 32px;
            max-width: 1200px;
            width: 100%;
            padding: 0 20px;
        }

        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-top: 125px;
        }

        .logo {
            position: absolute;
            top: 0px;
            left: 0px; 
            width: 450px;
        }

        h1, p {
            margin: 5px 0;
        }

        .formcontainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 800px;
            padding: 20px;
            background: #ffffffb0;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        .option input[type="radio"] {
            margin-bottom: 10px;
            width: 20px;
            height: 20px;
        }

        .inputquestion {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px;
            box-sizing: border-box;
            font-size: 32px;

        }

        .option {
            width: 100%;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .option input[type="text"] {
            flex-grow: 1;
            padding: 5px;
            border: 1px;
            box-sizing: border-box;
            margin-left: 10px;  
            font-size: 32px;
        }

        .inputquestion,
        .option input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 32px;
            outline: none; 
        }
        
        .createbtn,
        .submitbtn {
            padding: 10px;
            text-align: center;
            font-size: 32px;
            border: 3px solid black;
            border-radius: 15px;
            font-weight: 900;
            transition: background-color 0.3s ease;
            background: #E5CEA0;
            margin: 10px;
            display: inline-block;
            flex-direction: column;
            align-items: center;
        }

        .submitbtn {
            width: 400px;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-left: 50px;
            margin-top: 20px;
        }

        .createbtn:hover ,
        .submitbtn:hover {
            background-color: #d9c08c;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="Images/SnickSnack.png" alt="Logo" class="logo">
        <?php 
        echo '<h1 class="title">Title: '.$result1.'</h1>';
        echo '<p class="sub-title">Level: '.$LVLID.'</p>';
        echo "<p>Questions Generated: $question_count/10</p>";
        ?>
    </div>

    <form method="post">
        <div class="formcontainer">
            <input type="text" name="question" placeholder="Question" required class="inputquestion">
            <div class="option">
                <input type="radio" name="answer" value="A" required>A. <input type="text" name="option_A" id="option_A" placeholder="Option A" required>
            </div>
            <div class="option">
                <input type="radio" name="answer" value="B" required>B. <input type="text" name="option_B" id="option_B" placeholder="Option B" required>
            </div>
            <div class="option">
                <input type="radio" name="answer" value="C" required>C. <input type="text" name="option_C" id="option_C" placeholder="Option C" required>
            </div>
        </div>
        <div class="button-container">
            <input type="submit" name="submit" value="Create New Question" class="createbtn">
            <?php
                if ($question_count < 10) {
                    echo '<input type="button" class="submitbtn" onclick="alert(\'Please create at least 10 questions before uploading the quiz.\')" value="Upload Quiz">';
                } else {
                    echo '<input type="button" class="submitbtn" onclick="alert(\'Quiz uploaded successfully!\'); window.location.href = \'dashboard_admin.php\';" value="Upload Quiz">';
                }
            ?>
        </div>
    </form>
</body>
</html>
