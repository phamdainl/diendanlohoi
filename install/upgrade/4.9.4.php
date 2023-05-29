<?php

DB::table(PREFIX . 'codo_config')
    ->where('option_name', 'version')
    ->update(['option_value' => '4.9.4']);

DB::query('ALTER TABLE ' . PREFIX . 'codo_topics MODIFY COLUMN title VARCHAR(1000)');