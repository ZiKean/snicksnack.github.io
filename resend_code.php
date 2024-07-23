<?php
session_start();
include('conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function resend_email_verify($username, $email, $verify_token){
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
    $mail->addAddress($email);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Resend - Email verification from SnickSnack';
    $mail->Body    = "
        <h2>You have register with SnickSnack.</h2>
        <h5>Verify your email address to Login with the below given link</h5>
        <br><br>
        <a href='http://localhost/SnickSnack/verify_email.php?token=$verify_token'>Click Me</a>
    ";
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    $mail->send();
    //
}

if(isset($_POST['resend_email_verify_btn'])){
    if(!empty(trim($_POST['email_address']))){
        $email = mysqli_real_escape_string($con, $_POST['email_address']);

        $checkemail_query = "SELECT * FROM users WHERE Email='$email' LIMIT 1";
        $checkemail_query_run = mysqli_query($con, $checkemail_query);

        if(mysqli_num_rows($checkemail_query_run)> 0){
            $row = mysqli_fetch_array($checkemail_query_run);
            if($row['Verify_status'] == 0){
                $username = $row['Username'];
                $email = $row['Email'];
                $verify_token = $row['Verify_token'];
                resend_email_verify($username, $email, $verify_token);

                $_SESSION['status'] = "Verification link has been sent to your email address.<br>Please check your mailbox.";
                header("Location: login.php");
                exit(0);
            }
            else{
                $_SESSION['status'] = "The email address already verified. Please login.";
                header("Location: login.php");
                exit(0);
            }
        }
        else{
            $_SESSION['status'] = "The email address is not registered. Please register now.";
            header("Location: registration.php");
            exit(0);
        }
    }
    else{
        $_SESSION['status'] = "Please enter an email address.";
        header("Location: resend_email_verification.php");
        exit(0);
    }
}

?>