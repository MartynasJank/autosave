<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$body = $_POST['body'];
$subject = $_POST['subject'];
$to = $_POST['to'];

$mail = new PHPMailer;

$mail->isSMTP();                                            // Send using SMTP
$mail->Host       = 'lydeka.serveriai.lt';                    // Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->Username   = 'logistika@autopara.lt';                     // SMTP username
$mail->Password   = 'Airmeat1996';                               // SMTP password
$mail->SMTPSecure = "ssl";         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
$mail->Port       = 465;                                    // TCP port to connect to

$mail->setFrom('logistika@autopara.lt', 'Autopara Logistika');
$mail->addAddress($to);     // Add a recipient

if (array_key_exists('userfile', $_FILES)) {
    //Attach multiple files one by one
    for ($ct = 0; $ct < count($_FILES['userfile']['tmp_name']); $ct++) {
        $uploadfile = tempnam(sys_get_temp_dir(), sha1($_FILES['userfile']['name'][$ct]));
        $filename = $_FILES['userfile']['name'][$ct];
        if (move_uploaded_file($_FILES['userfile']['tmp_name'][$ct], $uploadfile)) {
            $mail->addAttachment($uploadfile, $filename);
        }
    }
}
$mail->isHTML(true);
$mail->Subject = $subject;
$mail->Body    = $body;
$mail->AltBody = $body;

if(!$mail->send()) {
    echo 'oopsie woopsie';
} else {
    session_destroy();
    header("Location: index.php");
}

?>
