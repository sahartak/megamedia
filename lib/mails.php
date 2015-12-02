<?php

include LIB . 'phpmailer/class.phpmailer.php';

function email_send ($to, $subject, $body, $attachemnt) {
	$email = new PHPMailer();
	$email->From      = 'orders@megamedia.dk';
	$email->FromName  = WEBSITE;
	$email->Subject   = $subject;
	$email->Body      = $body;

	foreach ($to as $_recipient) {
		if (!empty($_recipient)) {
			$email->AddAddress($_recipient);
		}
	}

	$email->AddAttachment($attachemnt, 'Order.pdf' );

	return $email->Send();
}
