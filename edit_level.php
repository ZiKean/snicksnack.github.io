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
    <style>
        body {
            background-image: url('Images/Background3.jpg');
            font-family: 'Kanit', sans-serif;
            display: flex;
            align-items: center;
            flex-direction: column;
        }

        img {
            width: 250px;
            margin: 10px;
        }

        .table-container {
            overflow: auto;
            max-height: 500px;
        }


        table th {
            font-weight: bold;
            font-size: 25px;
        }

        table td {
            font-size: 20px;
        }

        table th,
        table td {
            padding: 20px;
        }

        table th:nth-child(2),
        table td:nth-child(2) {
            padding-right: 400px;
        }

        input[type="text"],
        input[type="submit"], button {
            margin-top: 10px;
            padding: 5px;
            font-size: 20px;
        }

        ::-webkit-scrollbar {
                width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        button,
        .backbtn{
            padding: 10px;
            text-align: center;
            font-size: 16px;
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

        button:hover,
        .backbtn:hover {
            background-color: #d9c08c;
        }
    </style>
</head>
<body>
    <h1><img src="Images/SnickSnack.png" alt="Logo"></h1>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Level ID</th>
                    <th>Level Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("conn.php");

                $sql_level = "SELECT * FROM level";
                $result_level = mysqli_query($con, $sql_level);

                while($row = mysqli_fetch_assoc($result_level)) {
                    echo "<tr>";
                    echo "<td>" . $row['Level_ID'] . "</td>";
                    echo "<td>" . $row['LevelName'] . "</td>";
                    echo "<td><form action='edit_questions.php' method='GET'><input type='hidden' name='LVLID' value='" . $row['Level_ID'] . "'><button type='submit'>Edit</button></form></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <form action="dashboard_admin.php">
        <input type="submit" value="Back" class="backbtn">
    </form>
</body>
</html>
