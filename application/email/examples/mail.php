<?php
/**
 * This example shows making an SMTP connection with authentication.
 */

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

//require '../vendor/autoload.php';
require './src/Exception.php';
require './src/PHPMailer.php';
require './src/SMTP.php';
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Set the hostname of the mail server
$mail->Host = 'email.ils.res.in';
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
$mail->AuthType = 'TLS';
//Username to use for SMTP authentication
$mail->Username = 'imgsb@ils.res.in';
//Password to use for SMTP authentication
$mail->Password = 'pwd$$456';
//Set who the message is to be sent from
$mail->setFrom('imgsb@ils.res.in', 'ILS Server');
//Set an alternative reply-to address
$mail->addReplyTo('imgsb@ils.res.in', 'Feedback');
//Set who the message is to be sent to
$mail->addAddress('arupneo2@gmail.com', 'Arup');
//Set the subject line
$mail->Subject = 'BedSect Session'.$session_id;
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->isHTML(true);
$mail->Body = '<b>Job has been submitted with following details</b><br>Job id: '.$session_id.' <br>Genome: '.$genome.'<br> Please keep the session id for future reference. The the session id will become invalid after seven days from now.<br> Thank you for using BedSect toool hosted by Institute of Life Sciences, Bhubaneswar Web-Server. For queries please reply to this email, spamming will cause permanent ban from ILS server.';
#$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
//Replace the plain text body with one created manually
#$mail->AltBody = 'This is a plain-text message body';
//Attach an image file
#$mail->addAttachment('images/phpmailer_mini.png');

//send the message, check for errors
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message sent!';
}
