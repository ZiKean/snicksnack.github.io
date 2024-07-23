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
    document.addEventListener('DOMContentLoaded', function() {
        // Close the alert after 5 seconds
        setTimeout(function() {
            document.querySelector('.alert').style.display = 'none';
        }, 4000);

        document.querySelector('.back-btn').addEventListener('click', function() {
            window.location.href = 'index.php';
        });
    });

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
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-size: cover;
            font-family: 'Kanit', sans-serif;
        }

        .card {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 500px;
            padding: 20px;
            margin-bottom: 100px;    
        }

        .logo {
            display: flex;
            width: 550px;
            height: 400px;
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

        .register-text {
            font-size: 90px;
            font-family: 'Kanit', sans-serif;
            font-weight: 900;
            color: white;
            text-shadow: 8px 10px 0px rgba(0, 0, 0, 1);
            -webkit-text-stroke: 5px #528C46;
            text-align: center;
        }   

        .register-text span {
            display: block;
        }

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

        .segment {
            font-size: 18px;
            margin-top: 25px;
            display: flex; 
            align-items: center;
        }

        .Input {
            font-family: 'Kanit', sans-serif;
            font-size: 24px;
            border: 3px solid black; 
            outline: none;
            padding: 10px;
            border-radius: 0 30px 30px 0;
            box-sizing: border-box;
            flex: 1;
            max-height: 80px;
        }

        .password {
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

        .back-btn {
            display: flex;
            justify-content: left;
            align-items: center;
            margin-top: 20px;
        }

        .back-btn img {
            width: 100px;
            cursor: pointer; 
        }

        .register-btn {
            background: #E5CEA0;
            font-size: 20px;
            width: 200px;
            height: 60px;
            border: 3px solid black;
            border-radius: 25px;
            font-weight: 800;
            justify-content: right;
            align-items: center;
            margin-bottom: 20px;
            margin-top: auto;
            position: absolute;
            bottom: 20px;
            right: 20px;

        }

        .container {
            background: #DB6B5C;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 25px;
            border: 6px solid black;
            width: 850px;
            height: 850px;
            margin-left: 20px;
            position: relative; 
        }

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

        .gender-container {
            background-color: white;
            font-family: 'Kanit', sans-serif;
            font-size: 24px;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 0 30px 30px 0;
            box-sizing: border-box;
            border: 3px solid black;
            flex: 1;
            height: 62px;
        }

        .gender-option {
            display: inline-block;
            position: relative;
            padding-left: 30px;
            margin-right: 20px;
            cursor: pointer;
            font-size: 18px;
        }

        .gender-option input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #ccc;
            border-radius: 50%;
        }

        .gender-option:hover input ~ .checkmark {
            background-color: #aaa;
        }

        .gender-option input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .gender-option input:checked ~ .checkmark:after {
            display: block;
        }

        .gender-option .checkmark:after {
            top: 9px;
            left: 9px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: white;
        }
    </style>
</head>
<body style="background-image: url('Images/Background1.jpg'); background-size: cover;">

    <?php if(isset($_SESSION['status'])): ?>
    <div class="alert">
        <h4><?php echo $_SESSION['status']; ?></h4>
    </div>
    <?php unset($_SESSION['status']); endif; ?>
    
    <div class="card">
        <div class="logo">
            <img src="Images/SnickSnack.png" alt="SnickSnake_Logo" style="width: 100%; height: 100%;">
        </div><br>

        <div class="register-text"><I>
            <span>CREATE</span>
            <span>AN</span>
            <span>ACCOUNT</span>
        </I></div>
    </div>

    <div class="container">
        <form action="registration_insert.php" method="POST">
            <div class="segment">
                <label class="label">Username</label>
                <input type="text" name="username" class="Input">
            </div>
            <div class="segment">
                <label class="label">First Name</label>
                <input type="text" name="firstname" class="Input">
            </div>
            <div class="segment">
                <label class="label">Last Name</label>
                <input type="text" name="lastname" class="Input">
            </div>
            <div class="segment">
                <label class="label">Email Address</label>
                <input type="email" name="email_address" class="Input">
            </div>
            <div class="segment">
                <label class="label">Gender</label>
                <div class="gender-container">
                    <label class="gender-option">
                        <input type="radio" name="gender" value="male">
                        <span class="checkmark"></span>
                        Male
                    </label>
                    <label class="gender-option">
                        <input type="radio" name="gender" value="female">
                        <span class="checkmark"></span>
                        Female
                    </label>
                </div>
            </div>

            <div class="segment">
                <label class="label">Date of Birth</label>
                <input type="date" name="dob" class="Input">
            </div>
            <div class="segment">
                <label class="password">Password</label>
                <input type="password" id="password" name="password" class="Input">
                <input type="checkbox" onclick="myFunction('password')">Show Password
            </div>
            <div class="segment">
                <label class="password">Confirm Password</label>
                <input type="password" id="confirm-password" name="confirm-password" class="Input">
                <input type="checkbox" onclick="myFunction('confirm-password')">Show Password
            </div>

            <div class="back-btn">
                <img src="Images/pre_arrow.png" alt="Back">
            </div>

            <button type="submit" name="register_btn" class="register-btn">REGISTER NOW</button>
            </div>

        </form>
    </div>

</body>
</html>
