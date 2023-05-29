<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\Booter;

use CODOF\Hook;
use CODOF\Util;
use Illuminate\Database\Capsule\Manager as Capsule;

define('ABSPATH', (((dirname(dirname(__FILE__))))) . '/');
define('CODO_SITE', 'default');


if (@$_SERVER["HTTPS"] == "on") {
    $protocol = "https://";
} else {
    $protocol = "http://";
}

$path = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];

require ABSPATH . 'sys/CODOF/Booter/Load.php';

require ABSPATH . 'sites/' . CODO_SITE . '/constants.php';

\Constants::definePathConstants($path);

if (file_exists(DATA_PATH . 'config.php')) {

    //contains valuable db information
    require DATA_PATH . 'config.php';

    \Constants::defineConfigConstants($CONF);
    //contains routing system

    require ABSPATH . 'sys/vendor/autoload.php';

    $capsule = new Capsule();

    $config = get_codo_db_conf();

    $capsule->addConnection($config);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
   // $x = $container->make('db')->query('SELECT * FROM codo_config')->fetchAll();

    Hook::call('after_config_loaded');
    //Util::$use_normal_sessions = true;
    //Util::start_session();

    define('LOCALE', 'en_US');
    //loads translation system
    require DATA_PATH . 'locale/lang.php';

    require SYSPATH . 'globals/global.php';

    //initiate all plugins
    //Now the plugins can work on the data available
    //$plg = new \CODOF\Plugin();
    //$plg->init();
} else {

    die('sites/default/config.php does not exist.');
}
