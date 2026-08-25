<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Note: classes using this trait must set $mail properly protected/extended.
trait SendMail {

	public function sendMail($to, $subject, $message)
	{
		require_once __DIR__ . '/../../config.php';
		$smtp = app_config()['mail'];

		$mail = new PHPMailer(true);

		try {
			$mail->isSMTP();
			$mail->Host       = $smtp['host'];
			$mail->SMTPAuth   = true;
			$mail->Username   = $smtp['username'];
			$mail->Password   = $smtp['password'];
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
			$mail->Port       = $smtp['port'];

			$mail->setFrom($smtp['from']);
			$mail->addAddress($to);
			$mail->addReplyTo($smtp['from']);

			$mail->isHTML(true);
			$mail->Subject = $subject;
			$mail->Body    = $message;

			$mail->send();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

}