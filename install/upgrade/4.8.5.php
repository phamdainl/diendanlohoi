<?php

DB::table(PREFIX . 'codo_config')
    ->where('option_name', 'version')
    ->update(['option_value' => '4.8.5']);


if (!\CODOF\Upgrade\Upgrade::columnExists('codo_categories', 'default_subscription_type')) {
    Schema::table('codo_categories', function ($table) {
        $table->integer('default_subscription_type')->default(\CODOF\Forum\Notification\Subscriber::$DEFAULT);
    });
}

if (!function_exists('optionExists')) {

    function optionExists($option)
    {
        return (CODOF\Util::get_opt($option) != 'The option ' . $option . ' does not exist in the table');
    }
}

if (!optionExists("vote_up_notify_subject")) {
    DB::table(PREFIX . 'codo_config')->insert(array(
            array(
                'option_name' => 'vote_up_notify_subject',
                'option_value' => '[post:title] - vote up'
            ),
            array(
                'option_name' => 'vote_up_notify_message',
                'option_value' => "Hi, \n\n[user:username] has up voted your post in the topic: [post:title]\n\n----\n[post:omessage]\n----\n\nYou can view the post at the following url\n[post:url]\n\nRegards,\n[option:site_title] team\n"
            ),
            array(
                'option_name' => 'vote_down_notify_subject',
                'option_value' => '[post:title] - vote down'
            ),
            array(
                'option_name' => 'vote_down_notify_message',
                'option_value' => "Hi, \n\n[user:username] has down voted your post in the topic: [post:title]\n\n----\n[post:omessage]\n----\n\nYou can view the post at the following url\n[post:url]\n\nRegards,\n[option:site_title] team\n"
            )
        )
    );
}