<?php
DB::table ( PREFIX . 'codo_config' )->where ( 'option_name', 'version' )->update ( array (
		'option_value' => '3.7' 
) );

$permission = new \CODOF\Permission\Permission ();
$permission->addIfNotExists ( 'move posts', 'forum', array (
		ROLE_MODERATOR,
		ROLE_ADMIN 
) );

// remove any duplicates from codo_config
$names = \DB::table ( 'codo_config' )->get ();
$rows = array ();
foreach ( $names as $name ) {
	
	$rows [$name ['option_name']] = $name;
}

\DB::table ( 'codo_config' )->delete ();

\DB::table ( 'codo_config' )->insert ( $rows );

\DB::statement ( 'ALTER TABLE codo_config ADD UNIQUE(option_name)' );

if (! \CODOF\Upgrade\Upgrade::columnExists ( 'codo_notify_text', 'status_link' )) {
	
	\DB::statement ( 'ALTER TABLE codo_notify_text ADD COLUMN status_link int DEFAULT -1' );
}

if (! \CODOF\Upgrade\Upgrade::columnExists ( 'codo_users', 'last_notification_view_time' )) {
	
	\DB::statement ( 'ALTER TABLE codo_users ADD COLUMN last_notification_view_time int DEFAULT 0' );
}

if (! \CODOF\Upgrade\Upgrade::columnExists ( 'codo_categories', 'show_children' )) {
	
	\DB::statement ( 'ALTER TABLE codo_categories ADD COLUMN show_children int DEFAULT 1' );
}