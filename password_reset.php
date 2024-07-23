<?php
session_start();
include('conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function send_password_reset($get_name, $get_email, $token){
    $mail = new PHPMailer(true);
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->Username   = 'snicksnack286@gmail.com';                     //SMTP username
    $mail->Password   = 'ujryabiubfljruhj';                               //SMTP password

    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('snicksnack286@gmail.com', 'SnickSnack');
    $mail->addAddress($get_email);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Reset password notification from SnickSnack';
    $mail->Body    = "
        <h2>Hello.</h2>
        <h5>You are receiving this email because we received a password reset request for your account.</h5>
        <br><br>
        <a href='http://localhost/SnickSnack/password_change.php?token=$token&email=$get_email'>Click Me</a>
    ";
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();
    //
}

if(isset($_POST['password_reset_link'])){

    $email = mysqli_real_escape_string($con, $_POST['email_address']);
    $token = md5(rand());

    $check_email = "SELECT Email FROM users Where Email='$email' LIMIT 1";
    $check_email_run = mysqli_query($con, $check_email);

    if(!empty($email)){
        if(mysqli_num_rows($check_email_run) >0 ){
            //check exists email
            $row = mysqli_fetch_array($check_email_run);
            $get_name = $row['Username'];
            $get_email = $row['Email'];

            $update_token = "UPDATE users SET Verify_token= '$token' WHERE Email = '$get_email' LIMIT 1";
            $update_token_run = mysqli_query($con, $update_token);

            if($update_token_run){
                // Send password reset email
                send_password_reset($get_name, $get_email, $token);

                $_SESSION['status'] = "A password reset link has been sent to your email address.<br>Please check your mailbox.";
            } else {
                $_SESSION['status'] = "Something went wrong while updating the token.";
            }
        } else {
            $_SESSION['status'] = "No email address found.";
        }
    } else {
        $_SESSION['status'] = "Please enter the email address.";
    }
    // Redirect back to password_reset.php
    header("Location: password_forgot.php");
    exit();
}

if(isset($_POST['password_update'])){
    //check valid token
    $email = mysqli_real_escape_string($con, $_POST['email_address']);
    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);
    $token = mysqli_real_escape_string($con, $_POST['password_token']);

    if(!empty($token)){
        //check all field has been entering
        if(!empty($email) && !empty($new_password) && !empty($confirm_password)){
            $check_token = "SELECT Verify_token FROM users WHERE Verify_token='$token' LIMIT 1";
            $check_token_run = mysqli_query($con, $check_token);

            if(mysqli_num_rows($check_token_run) >0 ){
                //check new password euqal to confirm password
                if($new_password == $confirm_password){
                    $update_password = "UPDATE users SET password='$new_password' WHERE Verify_token='$token' LIMIT 1";
                    $update_password_run = mysqli_query($con, $update_password);

                    if($update_password_run){
                        //change a new token after update the new password. Previous token will be invalid.
                        $new_token = md5(rand());
                        $update_new_token  = "UPDATE users SET Verify_token='$new_token' WHERE Verify_token='$token' LIMIT 1";
                        $update_new_token_run = mysqli_query($con, $update_new_token);

                        $_SESSION['status'] = "New password updated successfully."  ;
                        header("Location: login.php");
                        exit(0);
                    }
                    else{
                        $_SESSION['status'] = "Did not update password. Something went wrong.";
                        header("Location: password_change.php?token=$token&email=$email");
                        exit(0);
                    }
                }
                else{
                    $_SESSION['status'] = "New password and confirm password does not match.";
                    header("Location: password_change.php?token=$token&email=$email");
                    exit(0);
                }
            }
            else{
                $_SESSION['status'] = "Invalid token.";
                header("Location: password_change.php?token=$token&email=$email");
                exit(0);
            }
        }
        else{
            $_SESSION['status'] = "All field are mandatory.";
            header("Location: password_change.php?token=$token&email=$email");
            exit(0);
        }
    }
    else{
        $_SESSION['status'] = "No token available.";
        header("Location: password_change.php");
        exit(0);
    }
}
?>