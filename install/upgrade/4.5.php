<?php

DB::table(PREFIX . 'codo_config')->where('option_name', 'version')->update(array(
    'option_value' => '4.5'
));

if (!function_exists('optionExists')) {

    function optionExists($option)
    {
        return (CODOF\Util::get_opt($option) != 'The option ' . $option . ' does not exist in the table');
    }
}

if (!optionExists('insert_oembed_videos')) {
    DB::table(PREFIX . 'codo_config')->insert(array(
        array(
            'option_name' => 'insert_oembed_videos',
            'option_value' => 'yes',
        )
    ));
}