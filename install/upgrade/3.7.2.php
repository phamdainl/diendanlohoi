<?php

DB::table ( PREFIX . 'codo_config' )->where ( 'option_name', 'version' )->update ( array (
		'option_value' => '3.7.2' 
) );

