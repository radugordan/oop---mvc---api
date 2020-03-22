<?php 
require_once('class.phpmailer.php');

class Mailer {
	protected $mailer;

	public function __construct(){
		$this->mailer = new PHPMailer();
	}

	public function sendMail($from, $to, $subject, $body){
		$this->mailer->isSMTP();
//        $this->mailer->SMTPDebug  = 3;
		$this->mailer->Host = 'smtp.mailtrap.io';
		$this->mailer->SMTPAuth = true; 
		$this->mailer->Username = 'f668889257d17d';
		$this->mailer->Password = '9c8d1bc65371cc';
		$this->mailer->SMTPSecure = 'tls'; 
		$this->mailer->Port = 465;
		$this->mailer->setFrom($from);
		$this->mailer->addAddress($to);     
		$this->mailer->isHTML(true);                                
		$this->mailer->Subject = $subject;
		$this->mailer->Body    = $body;

		if(!$this->mailer->send()) {
		     return 'Message could not be sent.';
		   // return 'Mailer Error: ' . $this->mailer->ErrorInfo;
		} else {
		    return 'Message has been sent';
		}
	}
}
