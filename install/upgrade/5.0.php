<?php

use CODOF\Cron\Cron;
use CODOF\Database\Schema;
use CODOF\Util;


/**
 * This function will be defined from next version, so for now we will define it again
 */
/**
 * Checks if table exists in the database
 * @param $tableName
 * @return bool
 */
if (!function_exists('tableExists')) {
    function tableExists($tableName): bool
    {
        $db = \DB::getPDO();
        $databaseName = \DB::connection()->getDatabaseName();

        $stmt = $db->prepare("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=:schemaName AND TABLE_NAME=:tableName");
        $stmt->execute(array("schemaName" => $databaseName, "tableName" => $tableName));
        $tables = $stmt->fetchAll();
        return count($tables) > 0;
    }
}

if (!tableExists('codo_import_data')) {
    Schema::create('codo_import_data', function ($table) {
        $table->increments('id');
        $table->text('data');
    });
}

DB::table(PREFIX . 'codo_config')
    ->where('option_name', 'version')
    ->update(['option_value' => '5.0']);


DB::statement('ALTER TABLE ' . PREFIX . 'codo_mail_queue MODIFY COLUMN body LONGTEXT');

if (!function_exists('optionExists')) {
    function optionExists($option)
    {
        return (CODOF\Util::get_opt($option) != 'The option ' . $option . ' does not exist in the table');
    }
}

if (!optionExists('mail_dequeue_batch_num')) {
    DB::table(PREFIX . 'codo_config')->insert(array(
        array(
            "option_name" => "mail_dequeue_batch_num",
            "option_value" => "10"
        )
    ));
}

if (!optionExists('sidebar_hide_topic_messages')) {
    DB::table(PREFIX . 'codo_config')->insert(array(
        array(
            "option_name" => "sidebar_hide_topic_messages",
            "option_value" => "off"
        ),
        array(
            "option_name" => "sidebar_infinite_scrolling",
            "option_value" => "on"
        ),
        array(
            "option_name" => "show_sticky_topics_without_permission",
            "option_value" => "no"
        ),
        array(
            "option_name" => "user_redirect_after_login",
            "option_value" => "topics"
        )
    ));
}


if (!optionExists('approval_notify_mails')) {
    DB::table(PREFIX . 'codo_config')->insert(array(
        array(
            "option_name" => "approval_notify_mails",
            "option_value" => ""
        ),
        array(
            "option_name" => "new_registration_subject",
            "option_value" => "A new user registration has been made"
        ),
        array(
            "option_name" => "new_registration_message",
            "option_value" => "Hi [this:name],<br/><br/>[user:username] has registered on the forum and is waiting for your approval.<br/> You can approve the user by logging to the backend and clicking <a href=\"[this:approveUrl]\">Moderation -> Approve users</a><br/><br/>Regards,<br/>[option:site_title] team"
        )
    ));
}

Util::set_opt('await_approval_message',
    "Dear [user:username],<br/><br/>Thank you for registering at [option:site_title]. Before we can activate your account one last step must be taken to complete your registration.<br/><br/>To complete your registration, please visit this URL: [this:confirm_url]<br/><br/>Your Username is: [user:username] <br/><br/>If you are still having problems signing up please contact a member of our support staff at [option:admin_email]<br/><br/>Regards,<br/>[option:site_title]");

if (!tableExists('codo_badges')) {
    Schema::create(PREFIX . 'codo_badges', function ($table) {
        $table->increments('id');
        $table->string('name', 255);
        $table->text('description');
        $table->text('location');
        $table->integer('view_order')->default(0);
    });


    DB::table(PREFIX . 'codo_badges')->insert(array(
        array(
            "name" => "Admin",
            "description" => "Sysadmin and site admin",
            "location" => "admin.svg"
        ),
        array(
            "name" => "Moderator",
            "description" => "Acts as a neutral participant in discussions and debate",
            "location" => "moderator.svg"
        ),
        array(
            "name" => "Verified",
            "description" => "Verified Identity",
            "location" => "verfied.svg"
        ),
        array(
            "name" => "Top Contributor",
            "description" => "Heart of the forum",
            "location" => "top_contributor.svg"
        ),
        array(
            "name" => "Gamer",
            "description" => "",
            "location" => "gamer.svg"
        ),
        array(
            "name" => "1st Prize",
            "description" => "",
            "location" => "cup1.svg"
        ),
        array(
            "name" => "2nd Prize",
            "description" => "",
            "location" => "cup2.svg"
        ),
        array(
            "name" => "3rd Prize",
            "description" => "",
            "location" => "cup3.svg"
        ),
    ));
}

if (!tableExists('codo_user_badges')) {
    Schema::create(PREFIX . 'codo_user_badges', function ($table) {
        $table->increments('id');
        $table->integer('user_id')->index('user_id_index');
        $table->integer('badge_id');
        $table->integer('rewarded_date');
        $table->integer('rewarded_by');
    });
}

$cron = new Cron();
$cron->set('badge_notify', 300, 'now');
