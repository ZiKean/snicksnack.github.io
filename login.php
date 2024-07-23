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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Kanit:wght@100;200;300;400;800&display=swap">
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

        /* Apply Kanit font to all elements */
        * {
            font-family: 'Kanit', sans-serif;
        }

        /* LOG IN TO + Logo */
        .container-header {
            display: flex;
            align-items: center;
            margin: 20px;
        }

        /* Text LOG IN TO */
        .login-text {
            font-size: 100px;
            font-weight: 900;
            color: white;
            text-shadow: 8px 10px 0px rgba(0, 0, 0, 1);
            margin-bottom: 20px;
            -webkit-text-stroke: 8px #8B2112;
        }

        /* Green background for login form */
        .container {
            background: #75DB5C;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 25px;
            border: 6px solid black;
            width: 600px;
            padding: 20px;
        }

        /* logo */
        .logo {
            display: flex;
            width: 350px;
            height: 250px;
            animation: shakeLogo 1s ease-in-out infinite alternate;
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

        /* Email and Password Input Set */
        .input-set {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Flex container for label and input */
        .flex-container {
            display: flex;
            align-items: center;
            width: 100%;
            margin-top: 25px;
        }

        /* Email Address and Password Label */
        .label {
            background: #E5CEA0;
            border-radius: 25px 0 0 25px;
            border: 3px solid black;
            padding: 10px;
            font-size: 24px;
            font-weight: 700;
            box-sizing: border-box;
            text-align: center;
            width: 250px;
        }

        /* Input bar */
        .Input {
            width: 100%;
            font-size: 24px;
            border: 3px solid black; 
            outline: none;
            padding: 10px;
            border-radius: 0 30px 25px 0;
            box-sizing: border-box;
            flex: 1;
        }

        .Input:focus {
            border-color: #8B2112; /* Add focus effect */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        /* Navigation link */
        .resend,
        .forgot-password,
        .sign-up {
            font-size: 20px;
        }

        /* Login button */
        .login-btn {
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            background: #DB6B5C;
            font-size: 32px;
            border: 6px solid black;
            border-radius: 25px;
            font-weight: 900;
            padding: 10px;
            padding-left: 20px;
            padding-right: 20px;
            margin: 10px;
            transition: all 0.3s ease; /* Add transition for smooth hover effect */
        }

        .login-btn:hover {
            background-color: #c22c21; /* Change background color on hover */
            transform: scale(1.05); /* Increase size on hover */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); /* Add shadow on hover */
        }



        .su-fp {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
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

        .checkbox {
            margin-top: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

    </style>
</head>
<body style="background-image: url('Images/Background1.jpg'); background-size: cover;">

    <?php if(isset($_SESSION['status'])): ?>
        <div class="alert">
            <h4><?php echo $_SESSION['status']; ?></h4>
        </div>
        <?php unset($_SESSION['status']); endif; ?>

    <div class="container-header">
        <span class="login-text">LOG IN TO</span>
        <div class="logo">
            <img src="Images/SnickSnack.png" alt="SnickSnack_Logo" style="width: 100%; height: 100%;">
        </div>
    </div>

    <div class="container">
        <form action="login_access.php" method="POST">
            <div class="input-set">
                <div class="flex-container">
                    <label class="label">EMAIL ADDRESS</label>
                    <input type="text" name="email_address" class="Input">
                </div>
            </div>

    <!-- Inside the form -->
    <div class="input-set">
        <div class="flex-container">
            <label class="label">PASSWORD</label>
            <input type="password" id="password" name="password" class="Input">
        </div>
        <div class="flex-container" style="justify-content: flex-end;">
            <label for="showPassword" class="checkbox">
                <input type="checkbox" id="showPassword" onclick="myFunction('password')">Show Password
            </label>
        </div>
    </div>


    <div class="su-fp">
        <div class="sign-up">
            Don't have an account? <a href="registration.php">Sign Up</a>
        </div>
        <div class="forgot-password">
            <a href="password_forgot.php">Forgot Password?</a>
        </div>
    </div>


            <button type="submit" name="login_now_btn" class="login-btn">LOG IN</button>
            <hr>
        </form>

        <div class="resend">
            Did not receive your verification email?
            <a href="resend_email_verification.php">Resend</a>
        </div>
        <br>
        <br>
    </div>
</body>
</html>
