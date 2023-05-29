<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\Forum\Notification;

use CODOF\Util;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class FastMail {

    /**
     * @var PHPMailer
     */
    public $mailer;

    public function __construct() {

        $this->mailer = new PHPMailer();
        $mail = $this->mailer;

        if (\CODOF\Util::get_opt('mail_type') == 'smtp') {
            $mail->IsSMTP(); // enable SMTP
            $mail->SMTPKeepAlive = true; // SMTP connection will not close after each email sent, reduces SMTP overhead
        } else {
            $mail->IsMail();
        }

        $smtpProtocol = Util::get_opt('smtp_protocol');
        $mail->SMTPAuth = true;  // authentication enabled
        if ($smtpProtocol === 'SSL') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else if ($smtpProtocol == 'TLS') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;
            $mail->SMTPAuth = false;
        }

        $mail->Host = \CODOF\Util::get_opt('smtp_server');
        $mail->Port = \CODOF\Util::get_opt('smtp_port');
        $mail->Username = \CODOF\Util::get_opt('smtp_username');
        $mail->Password = \CODOF\Util::get_opt('smtp_password');
        $mail->SetFrom(\CODOF\Util::get_opt('admin_email'), \CODOF\Util::get_opt('site_title'));
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
    }


    public function setSubjectAndBody($subject, $body) {
        $mail = $this->mailer;
        $mail->Subject = $subject;
        try {
            $mail->msgHTML($body);
        } catch (Exception $e) {
        }
        $mail->CharSet = "text/html; charset=UTF-8;";
        $mail->WordWrap = 60;
    }

    public function addAddress($address) {
        try {
            $this->mailer->addAddress($address);
        } catch (Exception $e) {
        }
    }

    public function sendMail() {
        $mail = $this->mailer;
        try {
            $mail->send();
        } catch (Exception $e) {
            \CODOF\Util::log('Mail error: ' . $mail->ErrorInfo);
            //Reset the connection to abort sending this message
            //The loop will continue trying to send to the rest of the list
            $mail->getSMTPInstance()->reset();
            return false;
        }

        //Clear all addresses and attachments for the next iteration
        $mail->clearAddresses();
        $mail->clearAttachments();
        return true;
    }

}
