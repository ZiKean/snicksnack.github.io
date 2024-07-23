<?php
include("conn.php");
session_start();


// Check if user is logged in
if (!isset($_SESSION['email'])) {
    $_SESSION['status'] = "Please log in to access this page.";
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'save') {
            // Save user data
            $username = $_POST['username'];
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $gender = $_POST['gender'];
            $dob = $_POST['dob'];

            $updateSql = "UPDATE users SET FirstName='$firstName', Lastname='$lastName', Email='$email', Gender='$gender', DOB='$dob' WHERE Username='$username'";
            if ($con->query($updateSql) === TRUE) {
                echo "success";
            } else {
                echo "Error updating user profile: " . $con->error;
            }
        } elseif ($_POST['action'] == 'delete') {
            // Delete user
            $username = $_POST['username'];

            $deleteSql = "DELETE FROM users WHERE Username='$username'";
            if ($con->query($deleteSql) === TRUE) {
                echo "success";
            } else {
                echo "Error deleting user: " . $con->error;
            }
        }
        exit; // Stop further execution
    }
}

// Fetch users
$sql = "SELECT * FROM users";
$result = $con->query($sql);
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
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
</head>
    <script>
        function saveUser(username) {
            var firstName = document.getElementById('editFirstName_' + username).innerText;
            var lastName = document.getElementById('editLastName_' + username).innerText;
            var email = document.getElementById('editEmail_' + username).innerText;
            var gender = document.getElementById('editGender_' + username).innerText;
            var dob = document.getElementById('editDOB_' + username).innerText;

            var formData = new FormData();
            formData.append('action', 'save');
            formData.append('username', username);
            formData.append('firstName', firstName);
            formData.append('lastName', lastName);
            formData.append('email', email);
            formData.append('gender', gender);
            formData.append('dob', dob);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    alert('User profile updated successfully.');
                } else {
                    alert('Error updating user profile.');
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function deleteUser(username) {
            if (confirm('Are you sure you want to delete this user?')) {
                var formData = new FormData();
                formData.append('action', 'delete');
                formData.append('username', username);

                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        alert('User deleted successfully.');
                        window.location.reload();
                    } else {
                        alert('Error deleting user.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: 'Kanit', sans-serif;
            
        }

        /*Background for login form */
        .container {
            background: #75DB5C;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 25px;
            border: 6px solid black;
            width: 600px;
        }

        /* alert */
        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: white;
            color: #8B2112;
            padding: 20px;
            border-radius: 5px;
            border: 3px solid black;
            font-size: 24px;
            z-index: 9999;
        }

        /* Table Styling */
        table {
            width: 80%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #E5CEA0;
            border: 3px solid black;
            font-weight: 900;
            font-size: 20px;
        }

        th, td {
            border: 3px solid black;
            padding: 10px;
            text-align: center;
        }

        th {
            border: 3px solid #000000;
            background-color: #e5cea0;
            border-radius: 18px;
            padding: 13px;
            margin-left: -2px;
            width: 200px;
            font-weight: 900;
        }

        td {
            background: #fff;
        }

        td[contenteditable="true"] {
            background: #f9f9f9;
        }

        button {
            background: #DB6B5C;
            border: 3px solid black;
            border-radius: 5px;
            padding: 5px 10px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #e57368;
        }

        .backbtn{
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

        .backbtn:hover{
            background-color: #d9c08c;
        }

        .logo{
            width: 300px;
            margin: 0px;
            top: -20px;
            left: 0px;
            position: absolute;
        }

        h1{
            font-size: 40px;
            margin-top: 100px;
            text-emphasis-style: filled; 
            text-emphasis-position: under; 
        }
    </style>
</head>
<body style="background-image: url('Images/Background3.jpg'); background-size: cover;">
    <div class="logo">
        <img src="Images/SnickSnack.png" alt="SnickSnake_Logo" style="width: 100%; height: 100%;">
    </div>
        
    <?php if (!empty($users)) : ?>
        <h1>User Details</h1>
        <table border="1">
            <tr>
                <th>Username</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Action</th>
            </tr>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?php echo $user['Username']; ?></td>
                    <td contenteditable="true" id="editFirstName_<?php echo $user['Username']; ?>"><?php echo $user['FirstName']; ?></td>
                    <td contenteditable="true" id="editLastName_<?php echo $user['Username']; ?>"><?php echo $user['Lastname']; ?></td>
                    <td contenteditable="true" id="editEmail_<?php echo $user['Username']; ?>"><?php echo $user['Email']; ?></td>
                    <td contenteditable="true" id="editGender_<?php echo $user['Username']; ?>"><?php echo $user['Gender']; ?></td>
                    <td contenteditable="true" id="editDOB_<?php echo $user['Username']; ?>"><?php echo $user['DOB']; ?></td>
                    <td>
                        <button onclick="saveUser('<?php echo $user['Username']; ?>')">Save</button>
                        <button onclick="deleteUser('<?php echo $user['Username']; ?>')">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?><br>

    <button class="backbtn" onclick="history.back()">Back</button>
</body>

</html>

<?php
// Close connection
$con->close();
?>
