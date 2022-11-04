
<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



class Mailer {

  
    
  public static function resetMail($fname, $email, $otp, $ReplyTo = "0")
  {
 require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

    if ($ReplyTo == "0") {
      // code...
      $ReplyTo = APP_MAIL;
    }


    $mail = new PHPMailer(true);
    
    $mail->Host = 'fulazo.io';
    
    $mail->SMTPAuth = true;
    
    $mail->Username = 'no-reply@fulazo.io';
    
    $mail->Password = 'MO=p{6p2Nv*(';
    
    $mail->SMTPSecure = 'ssl';
    
    $mail->Port = 465;
    
    $mail->setFrom('no-replys@fulazo.io', '' . APPNAME . '');
    
    $mail->addAddress($email, $fname);
    
    $mail->addReplyTo($ReplyTo, '' . APPNAME . '');
    
    $mail->isHTML(true);
    
    $mail->Subject = 'Reset Password';

    $mail->Body  =
      "<html>
    <body style=' padding: 0px; margin:1px; background-color: #f1f1f1'>
    <div style='background-color: #ffffff;border-radius: 10px;padding: 1.5rem;text-align: left'>
    <h1 style='text-align:center;color: black'>Your  - account  Reset <br> Details </h1>

    <h3>Hello $fname,</h3>

    <p styele='color: #3d3b3'>Use this code to access  your account.
    Your code is:<strong>$otp</strong>.
    After Logging in,  You can update your password to a preffered one
    <br>

    <br> Didn't
        request this  action? Please secure your account by changing your password. In order to protect your
        account security and  do not allow others to access your email.
         <br>
    <br> Thanks,  <br> <br> Team " . APPNAME . " </p>

    </div>

    </body>
    </html>
    ";
    if ($mail->send()) {

      return true;
    } else {

      return false;
    }
  }





}