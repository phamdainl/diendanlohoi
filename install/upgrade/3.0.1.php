<?php

DB::table(PREFIX . 'codo_config')
        ->where('option_name', 'version')
        ->update(array('option_value' => '3.0.1'));



$cids = DB::table(PREFIX . 'codo_categories')
        ->pluck('cat_id');

$manager = new \CODOF\Permission\Manager();

foreach ($cids as $cid) {
 
    $manager->copyCategoryPermissionsFromRole($cid);
}

