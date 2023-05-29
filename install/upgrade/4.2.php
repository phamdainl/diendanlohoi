<?php

DB::table(PREFIX . 'codo_config')->where('option_name', 'version')->update(array(
    'option_value' => '4.2'
));

Schema::dropIfExists(PREFIX . 'codo_attachments');
Schema::create(PREFIX . 'codo_attachments', function($table) {
    
    $table->increments('id');
    $table->string('original_name');
    $table->string('visible_hash')->unique();
    $table->string('stored_hash');
    $table->string('location');
    $table->integer('uid');
    $table->integer('upload_time');
    $table->integer('file_size');            
});
