<?php

$name = $_POST["name"];
$email = $_POST["email"];
$subject = $_POST["subject"];
$message = $_POST["message"];

require "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->Username = "amoskiprotich1130@gmail.com";
$mail->Password = "jbsiqaxldpxonsgf";

$mail->setFrom($email, $name);
$mail->addAddress("benfex254@gmail.com", "Kiprotich");

$mail->Subject = $subject;

// HTML email content
$mail->isHTML(true);
$mail->Body = "
    <html>
    <head>
        <style>
            body {
                font-family: 'Open Sans', Helvetica, Arial, sans-serif;
                line-height: 1.6;
                color: #37474f;
                max-width: 600px;
                margin: 0 auto;
                background-color: #f5f5f5;
            }
            .container {
                background-color: white;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }
            .header {
                background-color: #00796b;
                color: white;
                text-align: center;
                padding: 18px;
            }
            .content {
                padding: 25px;
            }
            .detail {
                margin-bottom: 15px;
                padding-bottom: 12px;
                border-bottom: 1px solid #e0e0e0;
            }
            .detail strong {
                color: #00796b;
                display: block;
                margin-bottom: 5px;
                font-weight: 600;
            }
            .message {
                background-color: #f1f8e9;
                border-left: 5px solid #00796b;
                padding: 15px;
                margin-top: 10px;
            }
            .footer {
                background-color: #e0e0e0;
                text-align: center;
                padding: 12px;
                font-size: 11px;
                color: #616161;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>BenFex Networks</h2>
            </div>
            <div class='content'>
                <div class='detail'>
                    <strong>From</strong>
                    $name
                </div>
                <div class='detail'>
                    <strong>Sender's Email</strong>
                    $email
                </div>
                <div class='detail'>
                    <strong>Topic</strong>
                    $subject
                </div>
                <div class='message'>
                    <strong>Message Body</strong> <br>
                    $message
                </div>
            </div>
            <div class='footer'>
                    This email was sent through BenFex website contact form <br>
                © " . date('Y') . " <strong>Kiprotich</strong> All Rights Reserved
            </div>
        </div>
    </body>
    </html>
";


try {
    $mail->send();
    echo json_encode(["status" => "success", "message" => "Your message has been sent."]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Failed to send message: " . $e->getMessage()]);
}
exit;