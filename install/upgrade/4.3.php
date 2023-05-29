<?php

DB::table(PREFIX . 'codo_config')->where('option_name', 'version')->update(array(
    'option_value' => '4.3'
));

if (!function_exists('optionExists')) {

    function optionExists($option) {

        return (CODOF\Util::get_opt($option) != 'The option ' . $option . ' does not exist in the table');
    }
}

if (!optionExists('GOOGLE_ID')) {
    DB::table(PREFIX . 'codo_config')->insert(array(
        array(
            'option_name' => 'GOOGLE_ID',
            'option_value' => 'NO_VAL',
        ),
        array(
            'option_name' => 'GOOGLE_SECRET',
            'option_value' => 'NO_VAL',
        ),
        array(
            'option_name' => 'GOOGLE_REDIRECT',
            'option_value' => 'NO_VAL',
        ),
        array(
            'option_name' => 'FB_ID',
            'option_value' => 'NO_VAL',
        ),
        array(
            'option_name' => 'FB_SECRET',
            'option_value' => 'NO_VAL',
        ),
        array(
            'option_name' => 'FB_REDIRECT',
            'option_value' => 'NO_VAL',
        ),
        array(
            'option_name' => 'TW_ID',
            'option_value' => 'NO_VAL',
        ),
        array(
            'option_name' => 'TW_SECRET',
            'option_value' => 'NO_VAL',
        ),
        array(
            'option_name' => 'TW_REDIRECT',
            'option_value' => 'NO_VAL',
        ),
        array(
            'option_name' => 'GITHUB_ID',
            'option_value' => 'NO_VAL',
        ),
        array(
            'option_name' => 'GITHUB_SECRET',
            'option_value' => 'NO_VAL',
        ),
        array(
            'option_name' => 'GITHUB_URL',
            'option_value' => 'NO_VAL',
        ),
        array(
            'option_name' => 'default_language',
            'option_value' => 'en_US',
        ),
        array(
            'option_name' => 'forum_logo',
            'option_value' => 'codoforum.png',
        )));
}