<?php

/**
 * Mailer:
 *
 * $this->mailer = new Mailer("jlramothale@gmail.com", "JR");
 * $this->mailer->sendMail("Testing 102", "This is another test message");
 *
 * @author Johannes Ramothale <jramothale@iecon.co.za>
 * @since 29 Jul 2017
 */
use PHPMailer\PHPMailer\PHPMailer;

require_once FRAMEWORK_PATH . 'mailer/Exception.php';
require_once FRAMEWORK_PATH . 'mailer/PHPMailer.php';
require_once FRAMEWORK_PATH . 'mailer/SMTP.php';

final class Mailer {

    /** @var string $host - The hostname to use sending the email */
    private $host = 'dedi240.cpt4.host-h.net';

    /** @var string $port - Host SMTP mail port */
    private $port = 587;

    /** @var string $sender - The username */
    private $username = 'support@myadminpal.com';

    /** @var string $username_description - The username description */
    private $username_description = "MyAdminPal Support Team";

    /** @var string $senderName - Name of the return address */
    private $password = 'Admin11@#12345';

    /** @var object $mail - The PHPMailer Object */
    private $mail = null;

    public function __construct($receiver, $receiver_name, $attachments = []) {
        $this->mail = new PHPMailer;
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->Host = $this->host;
        $this->mail->Port = $this->port;
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $this->username;
        $this->mail->Password = $this->password;

        $this->mail->setFrom($this->username, $this->username_description);
        $this->mail->addReplyTo($this->username, $this->username_description);
        $this->mail->addAddress($receiver, $receiver_name);

        foreach ($attachments as $attachment) {
            $this->mail->addAttachment($attachment);
        }
    }

    public function sendMail($subject, $body) {
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        $this->mail->AltBody = $body;

        if ($this->mail->send()) {
            return true;
        } else {
            return false;
        }
    }

}
