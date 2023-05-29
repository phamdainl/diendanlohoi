<?php

DB::table(PREFIX . 'codo_config')->where('option_name', 'version')->update(array(
    'option_value' => '4.1.2'
));


DB::statement("ALTER TABLE " . PREFIX . "codo_mail_queue MODIFY COLUMN mail_status int(11) NOT NULL DEFAULT 0;");
 
//TODO: Destructive query needs to be removed in next version 
//By default no category should be a label
DB::statement("ALTER TABLE " . PREFIX . "codo_categories MODIFY COLUMN is_label int DEFAULT 0;");