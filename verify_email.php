<?php
session_start();
include('conn.php');

if(isset($_GET['token'])){
    $token = $_GET['token'];
    $verify_query = "SELECT Verify_token, Verify_status FROM users WHERE Verify_token = '$token' LIMIT 1";
    $verify_query_run = mysqli_query($con, $verify_query);

    if(mysqli_num_rows($verify_query_run) > 0){
        $row = mysqli_fetch_array($verify_query_run);
        if($row['Verify_status'] == "0"){
            $clicked_token = $row['Verify_token'];
            $update_query = "UPDATE users SET Verify_status = '1' WHERE Verify_token = '$clicked_token' LIMIT 1";
            $update_query_run = mysqli_query($con, $update_query);

            if($update_query_run){
                $_SESSION['status'] = "Your account has been verified successfully.";
                header("Location: login.php");
                exit(0);
            }
            else{
                $_SESSION['status'] = "Verification failed.";
                header("Location: login.php");
                exit(0);
            }
        }
        else{
            $_SESSION['status'] = "This email address already verified. Please login.";
            header("Location: login.php");
            exit(0);
        }
    }
    else{
        $_SESSION['status'] = "This token does not exists";
        header("Location: login.php");
    }
}
else{
    $_SESSION['status'] = "Not Allowed";
    header("Location: login.php");
}
?>