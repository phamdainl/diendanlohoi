<?php

DB::table(PREFIX . 'codo_config')
        ->where('option_name', 'version')
        ->update(array('option_value' => '3.6'));

if (!function_exists('optionExists')) {

    function optionExists($option) {

        return (CODOF\Util::get_opt($option) != 'The option ' . $option . ' does not exist in the table');
    }
}

if (!optionExists('ml_spam_filter')) {
    DB::table(PREFIX . 'codo_config')->insert(array(
        array(
            'option_name' => 'ml_spam_filter',
            'option_value' => 'no',
    )));
}