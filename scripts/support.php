<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
if (isset($_POST['action']) && $_POST['action'] === 'support') {
  $email = test_input($_REQUEST['email']) ;
  $message = test_input($_REQUEST['message']) ;
  $name = test_input($_REQUEST['name']) ;
  $mail = new PHPMailer();
  $mail->IsSMTP();                                      // set mailer to use SMTP
  $mail->Host = "mail.betvibe.co";  // specify main and backup server
  $mail->SMTPAuth = true;     // turn on SMTP authentication
  $mail->Username = "support@betvibe.co";  // SMTP username
  $mail->Password = "betvibe.co"; // SMTP password
  
  $mail->From = $_POST['email'];
  $mail->FromName = $_POST['name'];
  $mail->AddAddress("support@betvibe.co");
  
  $mail->WordWrap = 50;                                 // set word wrap to 50 characters
  $mail->IsHTML(true);                                  // set email format to HTML
  
  $mail->Subject = 'support';
  $mail->Body    = $_POST['message'];
  $mail->AltBody = $_POST['message'];
  
  if(!$mail->Send())
  {
     echo "Message could not be sent. 
  ";
     echo "Mailer Error: " . $mail->ErrorInfo;
     exit;
  }
  
  echo "Message Successfully Sent";
}
?>