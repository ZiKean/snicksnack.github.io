<?php
session_start();
include('conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendemail_verify($username, $email, $verify_token){
    $mail = new PHPMailer(true);
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->Username   = 'snicksnack286@gmail.com';                     //SMTP username
    $mail->Password   = 'ujryabiubfljruhj';                               //SMTP password

    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
    $mail->Port       = 587;              //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('snicksnack286@gmail.com', 'SnickSnack');
    $mail->addAddress($email);     //Add a recipient

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Email verification from SnickSnack';
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

    if(isset($_POST['register_btn']))
    {
        $username = $_POST['username'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email_address'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password']; 
        $verify_token = md5(rand());
        
        // Check no empty field
        if(empty($username) || empty($firstname) || empty($lastname) || empty($email) || empty($gender) || empty($dob) || empty($password)) {
            $_SESSION['status'] = "All fields are mandatory.";
            header("Location: registration.php");
            exit();
        }

        // Check confirm password same as password
        if($password !== $confirmPassword) {
            $_SESSION['status'] = "Password and confirm password do not match.";
            header("Location: registration.php");
            exit();
        }

        // Check Email exists or not
        $check_email_query = "SELECT Email FROM users Where Email='$email' LIMIT 1";
        $check_email_query_run = mysqli_query($con, $check_email_query);

        if(mysqli_num_rows($check_email_query_run) > 0)
        {
            $_SESSION['status'] = "Email address already exists. Please enter different email address.";
            header("Location: registration.php");
            exit();
        }

        // Check Username exists or not
        $check_username_query = "SELECT Username FROM users WHERE Username='$username' LIMIT 1";
        $check_username_query_run = mysqli_query($con, $check_username_query);

        if(mysqli_num_rows($check_username_query_run) > 0)
        {
            $_SESSION['status'] = "Username already exists. Please enter different username.";
            header("Location: registration.php");
            exit();
        }
        else{
            //Insert user data
            //stmt = statement
            //bind_param = binding parameter
            //s = string
            //'?' = value provide later
            $query = "INSERT INTO users (`UserName`, `FirstName`, `Lastname`, `Email`, `Gender`, `DOB`, `Password`, `Verify_token`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ssssssss", $username, $firstname, $lastname, $email, $gender, $dob, $password, $verify_token);

            if($stmt->execute())
            {
                sendemail_verify("$username", "$email", "$verify_token");

                $_SESSION['status'] = "Registration successfully! Please verify your email address in your mail box.";
                header("Location: registration.php");
                exit();
            }
            else
            {
                $_SESSION['status'] = "Registration failed";
                header("Location: registration.php");
                exit();
            }
        }
    }
?>