    <?php
    include('conn.php');
    session_start();


    // Check if user is logged in
    if (!isset($_SESSION['email'])) {
        $_SESSION['status'] = "Please log in to access this page.";
        header("Location: login.php");
        exit();
    }

    // Fetch user data
    $email = $_SESSION['email'];
    $sql = "SELECT Username, Email, FirstName, LastName, Gender, DOB, Password FROM users WHERE Email = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the query was successful
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $username = $row['Username'];
        $email = $row['Email'];
        $firstname = $row['FirstName'];
        $lastname = $row['LastName'];
        $gender = $row['Gender'];
        $dob = $row['DOB'];
        $password = $row['Password'];
    } else {
        // Handle the case when no user data is found
        $username = "N/A";
        $email = "N/A";
        $firstname = "N/A";
        $lastname = "N/A";
        $gender = "N/A";
        $dob = "N/A";
        $password = "N/A";
    }

    // Check if the form is submitted for updating user information
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['field']) && isset($_POST['newValue'])) {
        // Update user information in the database
        $field = $_POST['field'];
        $newValue = $_POST['newValue'];

        $updateSql = "UPDATE users SET $field = '$newValue' WHERE Email = '$email'";

        if ($con->query($updateSql) === TRUE) {
            // Redirect to user profile page after successful update
            echo "<script>alert('Changes are saved.');</script>";
            header("Location: profile.php");
            exit();
        } else {
            // Handle errors if any
            echo "Error updating record: " . $con->error;
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SnickSnack</title>
        <link rel="icon" href="Images/Apple.png">
        <script>
            function showSavedMessage() {
            alert("Changes are saved.");
        }

        function validatePassword() {
            var newPassword = document.getElementById("newPassword").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            if (newPassword != confirmPassword) {
                alert("Passwords do not match!");
                return false;
            }
            return true;
        }
        </script>
        <style>
            body {
                background-image: url('Images/Background3.jpg');
                background-size: cover;
                font-size: 32px;
                font-family: 'Kanit', sans-serif;
                font-weight: 900;
                height: 100%;
            }

            .logo {
                position: absolute;
                top: -25px;
                left: 250px;
                width: 350px; /* Adjust the size as needed */
                height: auto;
            }   

            .container {
                margin-top: 90px; /* Adjust the margin-top to avoid overlapping with the logo */
                padding: 20px;
                background-color: #F0C535;
                border-radius: 20px;
                width: 50%;
                margin-left: auto;
                margin-right: auto;
                border: 6px solid black;
            }

            /* email address and password input bar */
            .bar-set {
                font-weight: 900;
                background: white;
                border-radius: 25px;
                border: 3px solid black;
                font-size: 24px;
                width: 100%;
                margin-top: 25px;
                display: flex; 
                align-items: center;
            }

            /* all label */
            .label {
                background: #E5CEA0;
                margin: -1px;
                border-radius: 25px;
                border: 3px solid black;
                padding: 18px;
                width: 200px;
                text-align: center;
            }

            .form {
                margin: 10px;
                font-size: 30px;  
                border: none; 
                outline: none;
            }

            .savebtn,
            .backbtn {
                font-size: 32px;
                border: 3px solid black;
                border-radius: 25px;
                font-weight: 900;
                padding: 5px;
                transition: background-color 0.3s ease;
                background: #E5CEA0;
            }

            .backbtn{
                margin-left: 100px;
            }

            .savebtn{
                margin-left: 180px;
            }

            button:hover {
                background-color: #d9c08c;
            }
        </style>
    </head>
    <body>
    <div class="container">
        <div class="bar-set">
            <label class="label">Username</label>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="showSavedMessage()">
                <input type="hidden" name="field" value="Username">
                <input type="text" name="newValue" placeholder="New Username" value="<?php echo htmlspecialchars($username); ?>" class="form"> 
                <button type="submit" class="savebtn">Save</button>
            </form>
        </div>
            
        <div class="bar-set">
            <label class="label">First Name</label>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="showSavedMessage()">
                <input type="hidden" name="field" value="FirstName">
                <input type="text" name="newValue" placeholder="New First Name" value="<?php echo htmlspecialchars($firstname); ?>" class="form">
                <button type="submit" class="savebtn">Save</button>
            </form>
        </div>

        <div class="bar-set">
            <label class="label">Last Name</label>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="showSavedMessage()">
                <input type="hidden" name="field" value="Lastname">
                <input type="text" name="newValue" placeholder="New Last Name" value="<?php echo htmlspecialchars($lastname); ?>" class="form">
                <button type="submit" class="savebtn">Save</button>
            </form>
        </div>

        <div class="bar-set">
            <label class="label">Date of Birth</label>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="showSavedMessage()">
                <input type="hidden" name="field" value="DOB">
                <input type="text" name="newValue" placeholder="New DOB" value="<?php echo htmlspecialchars($dob); ?>" class="form">
                <button type="submit" class="savebtn">Save</button>
            </form>
        </div>

        <div class="bar-set">
            <label class="label">Password</label>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validatePassword()">
                <input type="hidden" name="field" value="Password">
                <input type="password" name="newValue" id="newPassword" placeholder="New Password" class="form">
            </div>

            <div class="bar-set">
                <label class="label">Confirm Password</label>
                <input type="password" id="confirmPassword" placeholder="Confirm Password" class="form">
                <button type="submit" class="savebtn">Save</button>
            </form>
        </div>      
    </div>

    <form action="dashboard_user.php" method="post">
        <button type="submit" class="backbtn">BACK</button>
    </form>
    </body>
    </html>