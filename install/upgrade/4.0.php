<?php

DB::table(PREFIX . 'codo_config')->where('option_name', 'version')->update(array(
    'option_value' => '4.0'
));

DB::table(PREFIX . 'codo_config')->where('option_name', 'theme')->update(array(
    'option_value' => '2k18'
));

$permission = new \CODOF\Permission\Permission ();
$permission->addIfNotExists('add poll', 'forum', array(
    ROLE_USER, ROLE_MODERATOR, ROLE_ADMIN
));


if (!\CODOF\Upgrade\Upgrade::columnExists('codo_categories', 'is_label')) {

    \DB::statement('ALTER TABLE codo_categories ADD COLUMN is_label int DEFAULT 1');
}

Schema::dropIfExists(PREFIX . 'codo_poll_log');
Schema::create(PREFIX . 'codo_poll_log', function ($table) {

    $table->increments('id');
    $table->integer('poll_id');
    $table->integer('option_id');
    $table->integer('uid');
    $table->integer('active');
    $table->integer('voted_on');
});

Schema::dropIfExists(PREFIX . 'codo_poll_options');
Schema::create(PREFIX . 'codo_poll_options', function($table) {

    $table->increments('id');
    $table->integer('poll_id');
    $table->string('option_name');
    $table->integer('num_votes');
    $table->integer('option_status');
    $table->integer('option_created');
});

Schema::dropIfExists(PREFIX . 'codo_poll_questions');
Schema::create(PREFIX . 'codo_poll_questions', function($table) {

    $table->increments('id');
    $table->integer('topic_id')->unique();
    $table->string('question');
    $table->integer('num_votable');
    $table->integer('can_recast');
    $table->integer('total_votes')->default(0);
    $table->integer('public_vote_result');
    $table->integer('view_result_without_vote');
    $table->integer('end_time');
    $table->integer('is_active')->default(1);
});

