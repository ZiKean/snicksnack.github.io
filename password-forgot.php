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
            width: 800px; 
            margin-top: 100px;
            padding: 20px;
            border-radius: 15px;
        }

        /* email address and password input bar */
        .input-set {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Email Address and Password Label */
        .label {
            background: #DB6B5C;
            margin: -1px;
            border-radius: 10px;
            border: 3px solid black;
            padding: 10px;
            width: calc(100% - 300px);
        }

        /* Label + Input */
        .Email-bar {
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
        .send-btn {
            align-items: center;
            justify-content: center;
            background: #75DB5C;
            font-size: 24px;
            border: 3px solid black;
            border-radius: 25px;
            font-weight: 900;
            width: 1000px;
            margin-left: 500px;
        }
        .back-btn{
            display: flex;
            font-size: 100px;   
            margin-bottom: 20px;    
        }

        .button-container{
            display: flex;
            justify-content: space-between;
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
    </style>
</head>
<body style="background-image: url('Images/Background1.jpg'); background-size: cover;">
    <?php if(isset($_SESSION['status'])): ?>
    <div class="alert">
        <h4><?php echo $_SESSION['status']; ?></h4>
    </div>
    <?php unset($_SESSION['status']); endif; ?>
    
    <div class="container">
        <h1>Forgot Password</h1>
        <form action="password_reset.php" method="POST">
            <div class="input-set">
                <div class="Email-bar">
                    <label class="label">EMAIL ADDRESS</label>
                    <input type="email" name="email_address" class="Input" placeholder="Enter email address">
                </div>
            </div><br><br>
            <div class="button-container">
                <span class="back-btn" onclick="window.location.href = 'login.php';">&#129184;</span>

                <button type="submit" name="password_reset_link" class="send-btn">SEND PASSWORD RESET LINK</button>
            </div>
        </form>
    </div>
</body>
</html>

