<?php	
	function sendOTP($email,$otp) {
		// require('phpmailer/class.phpmailer.php');
		// require('phpmailer/class.smtp.php');
		require_once('../PHPMailer/PHPMailer-5.2-stable/PHPMailerAutoload.php');
		
		$from='erudite.onlinevoting@gmail.com';
		$password='eruditeproject';

		$message_body = "One Time Password for PHP login authentication is:<br/><br/>" . $otp;
	
		
		require_once('../PHPMailer/PHPMailer-5.2-stable/PHPMailerAutoload.php');
                $from='erudite.onlinevoting@gmail.com';
                $to=$email;
                $password='eruditeproject';
                $sub='Signup | Verification';
                $body=$message_body;
                $mail = new PHPMailer(); 
                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'ssl';
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = '465';
                $mail->isHTML();
                $mail->Username = $from;
                $mail->Password = $password;
                $mail->Subject = $sub;
                $mail->Body = $body;
				$mail->AddAddress($to);
				$result = $mail->Send();
                		
			return $result;
	}
?>