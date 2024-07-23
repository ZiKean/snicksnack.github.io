<?php
session_start();
include('conn.php');

if(isset($_POST['login_now_btn'])){
    // Make sure email and password are entered and sanitize inputs
    if(!empty(trim($_POST['email_address'])) && !empty(trim($_POST['password']))){
        $email = mysqli_real_escape_string($con, $_POST['email_address']);
        $password = mysqli_real_escape_string($con, $_POST['password']);

        // Admin login check
        $admin_query = "SELECT * FROM admin WHERE AdminEmail='$email' AND Password='$password' LIMIT 1";
        $admin_query_run = mysqli_query($con, $admin_query);

        if(mysqli_num_rows($admin_query_run) > 0){
            $_SESSION['status'] = "Admin login successful.";
            $_SESSION['email'] = $email;
            header("Location: dashboard_admin.php");
            exit(0);
        }

        // User login check
        $login_query = "SELECT * FROM users WHERE Email='$email' AND Password='$password' LIMIT 1";
        $login_query_run = mysqli_query($con, $login_query);

        if(mysqli_num_rows($login_query_run) > 0){
            $row = mysqli_fetch_array($login_query_run);

            if($row['Verify_status'] == '1'){
                $_SESSION['status'] = "You are logged in successfully.";
                $_SESSION['email'] = $email;
                $_SESSION['username'] = $row['Username'];
                header("Location: dashboard_user.php");
                exit(0);
            } else {
                $_SESSION['status'] = "Please verify your email address to login.";
                header("Location: login.php");
                exit(0);
            }
        } else {
            $_SESSION['status'] = "Invalid Email or Password.";
            header("Location: login.php");
            exit(0);
        }
    } else {
        $_SESSION['status'] = "All fields are mandatory.";
        header("Location: login.php");
        exit(0);
    }
}
?>