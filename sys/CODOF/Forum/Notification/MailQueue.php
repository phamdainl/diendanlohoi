<?php

namespace CODOF\Forum\Notification;

use CODOF\Util;

class MailQueue
{

    protected $db;

    public function __construct()
    {

        $this->db = \DB::getPDO();
    }


    /**
     * Send first N emails in codo_mails queue
     */
    public function dequeue()
    {
        $num = (int)Util::get_opt('mail_dequeue_batch_num');
        $qry = 'SELECT * FROM ' . PREFIX . 'codo_mail_queue WHERE mail_status=0 LIMIT ' . $num . ' OFFSET 0';
        $obj = $this->db->query($qry);

        $mails = $obj->fetchAll();
        if (!count($mails)) {
            return;
        }

        $mailer = new \CODOF\Forum\Notification\FastMail();

        foreach ($mails as $mail) {
            $mailer->setSubjectAndBody($mail['mail_subject'], \CODOF\Format::message($mail['body']));
            $mailer->addAddress($mail['to_address']);

            if($mailer->sendMail()) {
                $qry = 'DELETE FROM ' . PREFIX . 'codo_mail_queue WHERE id=' . $mail['id'];
                $this->db->query($qry);
            }
        }
    }

}
