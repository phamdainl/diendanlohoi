<?php

DB::table(PREFIX . 'codo_config')
        ->where('option_name', 'version')
        ->update(array('option_value' => '3.4'));

Schema::dropIfExists(PREFIX . 'codo_edits');
Schema::create(PREFIX . 'codo_edits', function($table) {
    $table->increments('id');
    $table->integer('post_id');
    $table->integer('uid');
    $table->text('text');
    $table->integer('time');
});


Schema::dropIfExists(PREFIX . 'codo_reputation');
Schema::create(PREFIX . 'codo_reputation', function($table) {
    $table->increments('id');
    $table->integer('from_id');
    $table->integer('to_id');
    $table->integer('post_id');
    $table->integer('points');
    $table->integer('rep_time');
});

Schema::dropIfExists(PREFIX . 'codo_daily_rep_log');
Schema::create(PREFIX . 'codo_daily_rep_log', function($table) {
    $table->integer('uid');
    $table->integer('rep_count');
    $table->integer('start_rep_time');
});

Schema::dropIfExists(PREFIX . 'codo_promotion_rules');
Schema::create(PREFIX . 'codo_promotion_rules', function($table) {
    $table->increments('id');
    $table->integer('reputation');
    $table->integer('posts');
    $table->integer('type');
    $table->integer('rid');
});

if (!function_exists('col_exists')) {

    function col_exists($table, $col) {

        $pdo = \DB::getPDO();
        $res = $pdo->query("SHOW COLUMNS FROM $table LIKE '$col'");

        return (bool) $res->fetch();
    }

}

if (!function_exists('optionExists')) {

    function optionExists($option) {

        return (CODOF\Util::get_opt($option) != 'The option ' . $option . ' does not exist in the table');
    }
}


if (!col_exists(PREFIX . 'codo_users', 'reputation')) {

    Schema::table(PREFIX . 'codo_users', function($table) {
        $table->integer('reputation')->default('0');
    });
}

if (!col_exists(PREFIX . 'codo_posts', 'reputation')) {

    Schema::table(PREFIX . 'codo_posts', function($table) {
        $table->integer('reputation')->default('0');
    });
}

if (!col_exists(PREFIX . 'codo_topics', 'redirect_to')) {

    Schema::table(PREFIX . 'codo_topics', function($table) {
        $table->integer('redirect_to')->nullable();
    });
}

if (!col_exists(PREFIX . 'codo_user_roles', 'is_promoted')) {

    Schema::table(PREFIX . 'codo_user_roles', function($table) {
        $table->integer('is_promoted')->nullable();
    });
}

if (!optionExists('max_rep_per_day')) {

    DB::table(PREFIX . 'codo_config')->insert(array(
        array(
            'option_name' => 'max_rep_per_day',
            'option_value' => 100,
        ),
        array(
            'option_name' => 'rep_req_to_inc',
            'option_value' => 0,
        ),
        array(
            'option_name' => 'posts_req_to_inc',
            'option_value' => 0,
        ),
        array(
            'option_name' => 'rep_req_to_dec',
            'option_value' => 0,
        ),
        array(
            'option_name' => 'posts_req_to_dec',
            'option_value' => 0,
        ),
        array(
            'option_name' => 'rep_times_same_user',
            'option_value' => 5,
        ),
        array(
            'option_name' => 'rep_hours_same_user',
            'option_value' => 24,
        ),
        array(
            'option_name' => 'enable_reputation',
            'option_value' => 'yes',
        )
    ));
}

$permission = new CODOF\Permission\Permission();

$permission->add('see history', 'forum', array(
    ROLE_USER, ROLE_MODERATOR, ROLE_ADMIN
));

$permission->add('rep up', 'forum', array(
    ROLE_USER, ROLE_MODERATOR, ROLE_ADMIN
));

$permission->add('rep down', 'forum', array(
    ROLE_USER, ROLE_MODERATOR, ROLE_ADMIN
));

$permission->add('merge topics', 'forum', array(
    ROLE_MODERATOR, ROLE_ADMIN
));

$permission->add('move topics', 'forum', array(
    ROLE_MODERATOR, ROLE_ADMIN
));

$permission->add('add tags', 'forum', array(
    ROLE_USER, ROLE_MODERATOR, ROLE_ADMIN
));
