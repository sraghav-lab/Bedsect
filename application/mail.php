<?php
/**
 * This example shows making an SMTP connection with authentication.
 */
#import data
//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

//require '../vendor/autoload.php';
require 'email/src/Exception.php';
require 'email/src/PHPMailer.php';
require 'email/src/SMTP.php';
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
//$mail->SMTPDebug = 2;
//Set the hostname of the mail server
$mail->Host = 'smtp_host';
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
$mail->AuthType = 'TLS';
//Username to use for SMTP authentication
$mail->Username = 'email@test.com';
//Password to use for SMTP authentication
$mail->Password = 'password';
//Set who the message is to be sent from
$mail->setFrom('email@test.com', 'BedSect server');
//Set an alternative reply-to address
$mail->addReplyTo('email@test.com', 'Reply');
//Set who the message is to be sent to
$mail->addAddress($email);
//Set the subject line
$mail->Subject = $subject; #"BedSect Session #".$_POST['session'];
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->isHTML(true);
$mail->Body = $mailbody;
$mail->send();