<?php

DB::table(PREFIX . 'codo_config')
        ->where('option_name', 'version')
        ->update(array('option_value' => '3.5'));

if (!function_exists('col_exists')) {

    function col_exists($table, $col) {

        $pdo = \DB::getPDO();
        $res = $pdo->query("SHOW COLUMNS FROM $table LIKE '$col'");

        return (bool) $res->fetch();
    }

}

if (!col_exists(PREFIX . 'codo_topics', 'topic_close')) {

    Schema::table(PREFIX . 'codo_topics', function($table) {
        $table->integer('topic_close')->default('0');
    });
}

$permission = new CODOF\Permission\Permission();
$permission->addIfNotExists('close topics', 'forum', array(
    ROLE_MODERATOR, ROLE_ADMIN
));

$cron = new \CODOF\Cron\Cron();
$cron->set('forum_update', 3600, 'now');


Schema::dropIfExists(PREFIX . 'codo_fields');
Schema::create(PREFIX . 'codo_fields', function($table) {

    $table->increments('id');
    $table->string('name', 100);
    $table->string('title', 200);
    $table->string('type', 50);
    $table->integer('show_reg');
    $table->integer('is_mandatory');
    $table->integer('show_profile');
    $table->integer('weight');
    $table->string('output_format', 500);
    $table->integer('enabled');
    $table->string('input_type', 50)->nullable();
    $table->integer('input_length');
    $table->text('data');
    $table->integer('hide_not_set');
    $table->string('def_value', 500);
});

Schema::dropIfExists(PREFIX . 'codo_fields_roles');
Schema::create(PREFIX . 'codo_fields_roles', function($table) {

    $table->integer('fid');
    $table->integer('rid');
    $table->primary(array('fid', 'rid'));
});

Schema::dropIfExists(PREFIX . 'codo_fields_values');
Schema::create(PREFIX . 'codo_fields_values', function($table) {

    $table->integer('uid');
    $table->integer('fid');
    $table->text('value');
    $table->primary(array('uid', 'fid'));
});


Schema::dropIfExists(PREFIX . 'codo_reports');
Schema::create(PREFIX . 'codo_reports', function($table) {

    $table->increments('id');
    $table->integer('topic_id');
    $table->integer('post_id');
    $table->integer('report_type');
    $table->string('details', 400);
    $table->integer('status');
    $table->integer('uid');
    $table->integer('time');
});

Schema::dropIfExists(PREFIX . 'codo_report_types');
Schema::create(PREFIX . 'codo_report_types', function($table) {

    $table->increments('id');
    $table->string('name', 310);
    $table->integer('count')->default('10');
    $table->integer('action')->default('1');
});


DB::table(PREFIX . 'codo_report_types')->insert(array(
    array('name' => 'Spam'), array('name' => 'Offtopic'), array('name' => 'Other')
        )
);
