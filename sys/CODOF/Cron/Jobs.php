<?php

/*
 * @CODOLICENSE
 */

/**
 *
 * This contains all the core jobs that are responsible for
 * maintenance, updates, indexing, etc
 *
 *
 */

namespace CODOF\Cron;

use CODOF\Forum\Post;
use CODOF\Log;
use CODOF\Service\BadgeService;

class Jobs
{

    public function run_jobs()
    {
        $this->unban_users();
        $this->close_topics();
    }

    public function add_core_hooks()
    {

        \CODOF\Hook::add('on_cron_notify', array(new \CODOF\Forum\Notification\Notifier, 'dequeueNotify'));
        \CODOF\Hook::add('on_cron_daily_digest', array(new \CODOF\Forum\Notification\Digest\Digest, 'sendDailyDigest'));
        \CODOF\Hook::add('on_cron_weekly_digest', array(new \CODOF\Forum\Notification\Digest\Digest, 'sendWeeklyDigest'));
        \CODOF\Hook::add('on_cron_mail_notify_send', array(new \CODOF\Forum\Notification\MailQueue(), 'dequeue'));
        //\CODOF\Hook::add('on_cron_mail_notify_send', array(new \CODOF\Forum\Notification\MailQueue(), 'dequeue'));
        \CODOF\Hook::add('on_cron_forum_update', array(new \CODOF\Forum\Forum(), 'update'));
        \CODOF\Hook::add('on_cron_badge_notify', array(new BadgeService(), 'addBadgeNotifications'));
        \CODOF\Hook::add('on_cron_reload_license_info', array(new \CODOF\Service\LicenseService(), 'reloadLicenseInfo'));

        // For now this is vBulletin specific hardcoded. TODO: Make it extensible/dynamic
        \CODOF\Hook::add('on_cron_import_bulk_data', function () {
            ini_set('memory_limit', '1024M');
            $imports = \DB::table(PREFIX . 'codo_import_data')->take(100)->get();
            foreach ($imports as $import) {
                $data = json_decode($import['data']);
                if (!isset($data->source)) {
                    \DB::table(PREFIX . 'codo_import_data')->where('id', '=', $import['id'])->delete();
                    continue;
                }
                @copy($data->source, $data->destination);
                $parts = explode('/', $data->destination);
                Post::saveAttachmentPreview(end($parts), $data->type == 'gallery' ? 200 : 683);
                \DB::table(PREFIX . 'codo_import_data')->where('id', '=', $import['id'])->delete();
            }

            if (count($imports) == 0) {
                $cron = new Cron();
                $cron->remove('import_bulk_data');
            }
        });


    }

    //Unbans all usernames/emails/ips that have passed the time limit
    //for ban period
    private function unban_users()
    {

        $qry = 'DELETE FROM ' . PREFIX . 'codo_bans WHERE ban_expires<' . time() . ' AND ban_expires<>0';
        $this->db->query($qry);
    }

    /**
     * Closes any topics which based on their auto close time
     */
    private function close_topics()
    {


    }

}