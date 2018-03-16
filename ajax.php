<?php

if($_SERVER['REQUEST_METHOD'] == 'POST') {


    // getting the captcha
    $captcha = "";
    if (isset($_POST["g-recaptcha-response"])) {  $captcha = $_POST["g-recaptcha-response"]; }


    if (!$captcha) {
        echo 'Captcha empty';
        exit;
    }

    $secret = "_ENTER_YOUR_SECRET_CODE"; //remember to add recaptcha key in index.html
    $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$_SERVER["REMOTE_ADDR"]), true);

    // if the captcha is cleared with google, send the mail and echo ok.
    if ($response["success"] != false) {

        // assemble the message from the POST fields
        $name       =   strip_tags(trim($_POST['name']));
        $email      =   filter_var(trim($_POST['email']),FILTER_SANITIZE_EMAIL);
        $message    =   trim($_POST['message']);

        if(empty($name) && empty($message) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {

            http_response_code(400);
            echo 'Something go wrong, try again';
            exit;
        }

        $mymail = '_ENTER_YOUR_EMAIL_';

        $subject = 'New Email from Ajax Form';
        $emailContent = 'Name: ' . $name . '    Email: ' . $email . '    Message: ' . $message;
        $email_headers = "From: $name <$email>";

        if (mail($mymail, $subject, $emailContent, $email_headers)) {

            http_response_code(200);
            echo "Thank You! Your message has been sent.";

        } else {

            http_response_code(500);

            echo "Oops! Something go wrong, try again";
        }




    } else {
        echo "Something go wrong";

        exit;
    }







} else {

    http_response_code(403);
    echo "There was a problem with your submission, please try again. I love hackers :*";
}


?>