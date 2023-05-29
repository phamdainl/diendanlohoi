<?php

DB::table(PREFIX . 'codo_config')
        ->where('option_name', 'version')
        ->update(array('option_value' => '2.6'));
        
DB::update("ALTER TABLE codo_smileys MODIFY COLUMN image_name VARCHAR(200)");        
