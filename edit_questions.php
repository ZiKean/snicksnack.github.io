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
            width: 300px;
            margin: 0px;
            top: 0px;
            left: 0px;
            position: absolute;
        }

        h1 {
            font-size: 30px;
            text-align: center;
            margin-top: 50px;
            text-decoration: underline overline;
        }

        label[for="level_name"] {
            font-size: 20px;
        }

        table th {
            font-weight: bold;
            font-size: 25px;
        }

        form {
            text-align: center;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="submit"] {
            margin-top: 10px;
            padding: 5px;
            font-size: 20px;
        }

        .labelname {
            border: 3px solid #000000;
            background-color: #e5cea0;
            border-radius: 18px;
            padding: 13px;
            margin-left: -2px;
            width: 200px;
        }

        .lvlname {
            width: 100%;
            margin: 10px;
            border: none;
            outline: none;
        }

        .lvlnameset {
            background: white;
            border-radius: 20px;
            width: 500px;
            display: flex;
            align-items: center;
            margin: auto;
        }

        .backbtn,
        .submitbtn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #e5cea0;
            color: #000000;
            text-decoration: none;
            font-weight: 800;
            border-radius: 20px;
            border: 3px solid #000000;
            transition: background-color 0.3s ease;
            font-size: 20px;
            margin-top: 20px;
            text-align: center;
        }

        .backbtn:hover,
        .submitbtn:hover {
            background-color: #d9c08c;
        }

        .select_answer {
            font-size: 20px;
            padding: 0 10px;
            height: 36px;
            margin-top: 8px;
        }
    </style>
</head>
<body>
<?php
include("conn.php");
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    $_SESSION['status'] = "Please log in to access this page.";
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['level_id'])) {
        $level_id = $_POST['level_id'];
        $level_name = $_POST['level_name'];
        $error_check = false;

        $sql_update_level = "UPDATE level SET LevelName = '$level_name' WHERE Level_ID = $level_id";
        $result_update_level = mysqli_query($con, $sql_update_level);
        if (!$result_update_level) {
            echo "Error updating level name: " . mysqli_error($con);
        }

        foreach ($_POST['question_id'] as $key => $question_id) {
            $question = $_POST['question'][$key];
            $option_A = $_POST['option_A'][$key];
            $option_B = $_POST['option_B'][$key];
            $option_C = $_POST['option_C'][$key];
            $answer = $_POST['answer'][$key];

            if (!in_array($answer, ['A', 'B', 'C'])) {
                $error_check = true;
                echo "<script>alert('Invalid answer for Question $question. Only A, B or C.');</script>";
            } else {
                $sql_update = "UPDATE question SET
                                Question = '$question',
                                Option_A = '$option_A',
                                Option_B = '$option_B',
                                Option_C = '$option_C',
                                Answer = '$answer'
                                WHERE Question_ID = $question_id";
                $result_update = mysqli_query($con, $sql_update);

                if (!$result_update) {
                    echo "Error updating questions: " . mysqli_error($con);
                }
            }
        }

        if (!$error_check) {
            echo "<script>alert('Updated Successfully');</script>";
        }
    } else {
        echo "Level ID not provided.";
    }
}
?>

<?php
if (isset($_GET['LVLID'])) {
    $level_id = $_GET['LVLID'];

    $sql_quiz_name = "SELECT LevelName FROM level WHERE Level_ID = $level_id";
    $result_quiz_name = mysqli_query($con, $sql_quiz_name);
    $row_quiz_name = mysqli_fetch_assoc($result_quiz_name);
    $quiz_name = $row_quiz_name['LevelName'];

    $sql_questions = "SELECT * FROM question WHERE Level_ID = $level_id";
    $result_questions = mysqli_query($con, $sql_questions);
?>
    <img src="Images/SnickSnack.png" alt="Logo">
    <h1>Update for Level <?php echo $level_id; ?></h1>

    <form action="" method="post">

        <input type="hidden" name="level_id" value="<?php echo $level_id; ?>">

        <div class="lvlnameset">
            <label for="level_name" class="labelname">Level Name:</label>
            <input type="text" id="level_name" name="level_name" class="lvlname" value="<?php echo $quiz_name; ?>"><br><br>  
        </div><br><br>

        <table>
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Option A</th>
                    <th>Option B</th>
                    <th>Option C</th>
                    <th>Answer</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result_questions)) { ?>
                    <tr>
                        <input type="hidden" name="question_id[]" value="<?php echo $row['Question_ID']; ?>">
                        <td><input type="text" name="question[]" value="<?php echo $row['Question']; ?>"></td>
                        <td><input type="text" name="option_A[]" value="<?php echo $row['Option_A']; ?>"></td>
                        <td><input type="text" name="option_B[]" value="<?php echo $row['Option_B']; ?>"></td>
                        <td><input type="text" name="option_C[]" value="<?php echo $row['Option_C']; ?>"></td>
                        <td>
                            <select name="answer[]" class="select_answer">
                                <option value="A" <?php if ($row['Answer'] == 'A') echo 'selected'; ?>>A</option>
                                <option value="B" <?php if ($row['Answer'] == 'B') echo 'selected'; ?>>B</option>
                                <option value="C" <?php if ($row['Answer'] == 'C') echo 'selected'; ?>>C</option>
                            </select>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <button type="submit" class="submitbtn">UPDATE</button><br>
        <a href="edit_level.php" class="backbtn">BACK</a>
    </form>
<?php
} else {
    echo "Level ID not provided.";
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[type="text"]').forEach(function(field) {
        field.addEventListener('click', function() {
            if (field.tagName.toLowerCase() === 'input') {
                let textarea = document.createElement('textarea');
                textarea.name = field.name;
                textarea.value = field.value;
                textarea.style.width = field.style.width;
                textarea.style.fontSize = '20px';
                textarea.style.marginTop = '10px';

                textarea.addEventListener('blur', function() {
                    if (textarea.value.trim() === '') {
                        let input = document.createElement('input');
                        input.type = 'text';
                        input.name = textarea.name;
                        input.value = textarea.value;
                        input.style.width = textarea.style.width;
                        input.style.fontSize = '20px';
                        input.style.marginTop = '10px';
                        addDynamicFieldEvent(input);
                        textarea.replaceWith(input);
                    } else {
                        textarea.replaceWith(field);
                        field.value = textarea.value;
                    }
                });

                field.replaceWith(textarea);
                textarea.focus();
            }
        });
    });
});

function addDynamicFieldEvent(field) {
    field.addEventListener('click', function() {
        if (field.tagName.toLowerCase() === 'input') {
            let textarea = document.createElement('textarea');
            textarea.name = field.name;
            textarea.value = field.value;
            textarea.style.width = field.style.width;
            textarea.style.fontSize = '20px';
            textarea.style.marginTop = '10px';

            textarea.addEventListener('blur', function() {
                if (textarea.value.trim() === '') {
                    let input = document.createElement('input');
                    input.type = 'text';
                    input.name = textarea.name;
                    input.value = textarea.value;
                    input.style.width = textarea.style.width;
                    input.style.fontSize = '20px';
                    input.style.marginTop = '10px';
                    addDynamicFieldEvent(input);
                    textarea.replaceWith(input);
                } else {
                    textarea.replaceWith(field);
                    field.value = textarea.value;
                }
            });

            field.replaceWith(textarea);
            textarea.focus();
        }
    });
}
</script>

</body>
</html>