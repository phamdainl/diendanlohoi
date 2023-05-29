<?php

DB::table(PREFIX . 'codo_config')
        ->where('option_name', 'version')
        ->update(array('option_value' => '3.5.1'));

if (!function_exists('optionExists')) {

    function optionExists($option) {

        return (CODOF\Util::get_opt($option) != 'The option ' . $option . ' does not exist in the table');
    }
}

if (!optionExists('forum_tags_num')) {
    DB::table(PREFIX . 'codo_config')->insert(array(
        array(
            'option_name' => 'forum_tags_num',
            'option_value' => 5,
        ),
        array(
            'option_name' => 'forum_tags_len',
            'option_value' => 15,
        ),
    ));
}
$permission = new CODOF\Permission\Permission();
$permission->addIfNotExists('report topics', 'forum', array(
    ROLE_MODERATOR, ROLE_ADMIN
));
