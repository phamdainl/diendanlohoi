<?php

use CODOF\Util;

DB::table(PREFIX . 'codo_config')
    ->where('option_name', 'version')
    ->update([ 'option_value' => '4.8.0']);

Schema::table(PREFIX . 'codo_roles', function($table) {
    $table->text('color')->nullable();
});

Util::set_opt("forum_header_menu", "site_title");
Util::set_opt("login_by", "USERNAME");