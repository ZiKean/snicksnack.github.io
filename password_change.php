<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnickSnack</title>
    <link rel="icon" href="Images/Apple.png">
    <script>
    // Close the alert after 5 seconds
    setTimeout(function() {
        document.querySelector('.alert').style.display = 'none';
    }, 4000);

    function myFunction(inputId) {
        var x = document.getElementById(inputId);
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
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

        /* Frame */
        .container {
            text-align: center;
            width: 700px; 
            margin-top: 100px;
            padding: 20px;
            border-radius: 15px;
        }

        /* email address and password input bar */
        .input-set {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center
        }

        /* Email Address and Password Label */
        .label {
            background: #DB6B5C;
            margin: -1px;
            border-radius: 10px;
            border: 3px solid black;
            padding: 10px;
            width: calc(100% - 100px);
        }

        /* Label + Input */
        .Email-bar, 
        .NPassword-bar,
        .CPassword-bar {
            font-weight: 900;
            background: white;
            border-radius: 10px;
            border: 3px solid black;
            font-size: 24px;
            width: 100%;
            margin-top: 25px;
            display: flex; 
            align-items: center;
        }

        /* Input bar */
        .Input {
            width: calc(100% - 10px);
            margin: 10px;
            font-size: 24px;
            border: none; 
            outline: none;
        }

        /* update button */
        .update-btn {
            align-items: center;
            justify-content: center;
            background: #75DB5C;
            font-size: 24px;
            border: 3px solid black;
            border-radius: 25px;
            font-weight: 900;
            padding: 10px 20px;
            margin-left: 380px;
        }

        /* alert */
        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: white;
            color: #8B2112;
            padding: 16px;
            border-radius: 5px;
            border: 3px solid black;
            font-size: 24px;
            z-index: 9999;
        }

        .checkbox{
            margin-left: 550px;
        }
    </style>
</head>
<body style="background-image: url('Images/Background1.jpg'); background-size: cover;">
    <?php if(isset($_SESSION['status'])): ?>
    <div class="alert">
        <h4><?php echo $_SESSION['status']; ?></h4>
    </div>
    <?php unset($_SESSION['status']); endif; ?>

    <div class="container">
        <h1>Change Your New Password</h1>
        <form action="password_reset.php" method="POST">
            <input type="hidden" name="password_token" value="<?php if(isset($_GET['token'])){echo $_GET['token'];}?>">

            <div class="input-set">
                <div class="Email-bar">
                    <label class="label">EMAIL ADDRESS</label>
                    <input type="text" name="email_address" value="<?php if(isset($_GET['email'])){echo $_GET['email'];}?>" 
                    class="Input" placeholder="Enter email address">
                </div>
            </div>

            <div class="input-set">
                <div class="NPassword-bar">
                    <label class="label">NEW PASSWORD</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Enter new password" class="Input">
                </div>
            </div>
            <input type="checkbox" class="checkbox" onclick="myFunction('new_password')">Show Password
            
            <div class="input-set">
                <div class="CPassword-bar">
                    <label class="label">CONFIRM PASSWORD</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Enter confirm password" class="Input">
                </div>
            </div>
            <input type="checkbox" class="checkbox" onclick="myFunction('confirm_password')">Show Password

            <br><br>
            <button type="submit" name="password_update" class="update-btn">UPDATE PASSWORD</button>
        </form>
    </div>
</body>
</html>
