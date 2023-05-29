<?php

DB::table(PREFIX . 'codo_config')
    ->where('option_name', 'version')
    ->update(['option_value' => '4.9.2']);

if (!\CODOF\Util::optionExists('force_https')) {
    DB::table(PREFIX . 'codo_config')->insert(array(
            array(
                'option_name' => 'force_https',
                'option_value' => 'no',
            )
        )
    );
}
