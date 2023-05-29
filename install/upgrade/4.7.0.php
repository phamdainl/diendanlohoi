<?php

DB::table(PREFIX . 'codo_config')
    ->where('option_name', 'version')
    ->update([ 'option_value' => '4.7.0']);

DB::table(PREFIX . 'codo_permissions')
    ->where('permission', '=', 'view forum')
    ->update(['granted' => '1']);

DB::table(PREFIX . 'codo_permissions')
    ->where('permission', '=', 'edit profile')
    ->update(['granted' => '0']);

DB::table(PREFIX . 'codo_permissions')
    ->where('permission', '=', 'edit profile')
    ->where('rid', '=', ROLE_ADMIN)
    ->update(['granted' => '1']);