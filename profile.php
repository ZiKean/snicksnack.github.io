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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = ['status' => 'error', 'message' => 'An error occurred'];
    
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'delete') {
            // Delete user
            $username = $_SESSION['username']; // Use session variable to avoid tampering

            $deleteSql = "DELETE FROM users WHERE Username=?";
            $stmt = $con->prepare($deleteSql);
            $stmt->bind_param('s', $username);

            if ($stmt->execute()) {
                session_destroy(); // End the session after deleting the user
                $response['status'] = 'success';
                $response['message'] = 'Account deleted successfully.';
            } else {
                $response['message'] = "Error deleting user: " . $stmt->error;
            }

            echo json_encode($response);
            exit; // Stop further execution
        }
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
        function deleteUser() {
            if (confirm('Are you sure you want to delete this user?')) {
                var formData = new FormData();
                formData.append('action', 'delete');

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        window.location.href = 'login.php';
                    } else {
                        alert('Error deleting user: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
    <style>
        body {
            background-image: url('Images/Background3.jpg');
            background-size: cover;
            font-size: 24px; 
            font-family: 'Kanit', sans-serif;
            font-weight: 500;
            margin: 0;
            padding: 20px;
            box-sizing: border-box; 
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            padding: 20px 50px;
            background-color: #F0C535;
            border-radius: 20px;
            max-width: 60%;
            border: 6px solid black;
            overflow: hidden;
            margin-top: 20px;
            flex: 1;
        }

        /* Bar styles */
        .bar-set {
            font-weight: 500;
            background: white;
            border-radius: 25px;
            border: 3px solid black;
            font-size: 24px;
            width: 100%;
            margin-top: 15px;
            display: flex;
            align-items: center;
        }

        /* Label styles */
        .label {
            background: #E5CEA0;
            margin: -1px;
            border-radius: 25px;
            border: 3px solid black;
            padding: 10px;
            width: 200px;
            text-align: center;
            font-weight: 800;
        }

        /* Additional styling for spans */
        span {
            margin-left: 10px;
        }

        /* Button styles */
        button {
            font-family: 'Kanit', sans-serif;
            font-size: 20px;
            border: 3px solid black;
            border-radius: 25px;
            font-weight: 900;
            padding: 10px 30px;
            margin: 10px;
            transition: background-color 0.3s ease;
            margin-top: 30px;
        }

        /* Button hover effect */
        button:hover {
            background-color: #d9c08c;
        }

        .deletebtn:hover {
            background-color: #fc4b38;
        }

        /* Different button colors */
        .backbtn,
        .editbtn {
            background: #E5CEA0;
        }

        .deletebtn {
            background: #cf4e40;
        }

        /* Button container */
        .button-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<body>
    <div style="display: flex; align-items: center;">
        <img src="Images/SnickSnack.png" alt="SnickSnack Logo" style="width: 200px; height: auto; margin-top: 20px;">
        <h1 style="margin-left: 10px;">Your Profile</h1>
    </div>

    <div class="container">
        <div class="bar-set">
            <label class="label">Username</label>
            <span><?php echo $username; ?></span>
        </div>

        <div class="bar-set">
           <label class="label">First Name</label>
           <span><?php echo $firstname; ?></span><br>
        </div>

        <div class="bar-set">
            <label class="label">Last Name</label>
            <span><?php echo $lastname; ?></span><br>
        </div>
        
        <div class="bar-set">
            <label class="label">Email Address</label>
            <span><?php echo $email; ?></span><br>
        </div>

        <div class="bar-set">
            <label class="label">Gender</label>
            <span><?php echo $gender; ?></span><br>
        </div>  

        <div class="bar-set">
            <label class="label">Date of Birth</label>
            <span><?php echo $dob; ?></span><br>
        </div>  

        <div class="bar-set">
            <label class="label">Password</label>
            <span><?php echo $password; ?></span>
        </div> 
    </div>

    <div class="button-container">
        <form action="dashboard_user.php" method="post">
            <button type="submit" class="backbtn">BACK</button>
        </form>
        <form action="profile_edit.php" method="post">
            <button type="submit" class="editbtn">EDIT</button>
        </form>
        
        <button type="submit" class="deletebtn" onclick="deleteUser()">Delete Account</button>
    </div>

</body>
</html>
