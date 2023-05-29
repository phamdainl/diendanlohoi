<?php session_start();

/*
 * @CODOLICENSE
 */

//defined('IN_CODOF') or die();
//defined('CODO_LICENSE') or die();
use Illuminate\Database\Capsule\Manager as Capsule;

ini_set('max_execution_time', 300);

class Step3 {

    public function write_conf() {

        $conf = "<?php

/* 
 * @CODOLICENSE
 */

defined('IN_CODOF') or die();

\$CF_installed=false;

function get_codo_db_conf() {


    \$config = " . $this->dbConfig . ";

    return \$config;
}

\$DB = get_codo_db_conf();

\$CONF = array (
    
  'driver' => 'Custom',
  'UID'    => '" . uniqid() . "',
  'SECRET' => '" . uniqid() . "',
  'PREFIX' => ''
);
";

        file_put_contents(ABSPATH . 'sites/default/config.php', $conf);
    }

    public function connect_db() {

        $driver = $_POST['db_driver'];
        $database = $_POST['db_name'];

        switch ($driver) {

            case 'sqlite':
                $config = array(
                    'driver' => 'sqlite',
                    'database' => $database,
                    'prefix' => '',
                );
                break;

            case 'mysql':
            case 'pgsql':
            case 'sqlsrv':
                $config = array(
                    'driver' => $driver,
                    'host' => $_POST['db_host'],
                    'database' => $database,
                    'username' => $_POST['db_user'],
                    'password' => $_POST['db_pass'],
                    'prefix' => '',
                );

            case 'mysql':
                $config['charset'] = 'utf8';
                $config['collation'] = 'utf8_unicode_ci';
                break;

            case 'pgsql':
                $config['charset'] = 'utf8';
                $config['schema'] = 'public';
        }

        if ($driver == 'sqlite') {

            @file_put_contents($database, '');
            @chmod($database, 0777);
            if (!is_writable($database)) {

                return 'Your database file is not writable. ';
            }
        }

        $capsule = new Capsule;

        $capsule->addConnection($config, 'test');

        $this->dbConfig = var_export($config, true);

        try {

            $connection = $capsule->getConnection('test');
        } catch (Exception $e) {

            if($e->getCode() == 1049)
            {
                return "The database name given does not exists.";
            }
            else if($e->getCode() == 1045)
            {
                return "Access was denied! We could not connect to the database with the given username/password. <br/>The given username/password is either not correct or the given user does not have access to the database provided";
            }
            return $e;
        }

        try {
            $connection->getPdo()->query("SELECT * FROM codo_topics");
        }
        catch (Exception $e) {
            if($e->getCode() === "42S02" ) {
                // Table not exists exception
                return "success";
            }
            else {
                return $e->getMessage();
            }
        }

        if(!isset($_SESSION['data_loss_warning_given']) || !$_SESSION['data_loss_warning_given']) {
            $_SESSION['data_loss_warning_given'] = true;
            return "Codoforum tables already exist in $database. If you still want to install press 'Try Again' button to continue but this will delete all <b>existing data!</b>";
        } else {
            $_SESSION['data_loss_warning_given'] = false;
            return "success";
        }
    }

    public function create_tables() {

        require '2014_10_02_074430_create_codoforum_database.php';
        $database = new codoforumInstallDatabase();

        $database->down(); //drop all tables
        $database->up(); //create all tables
        $database->fill(); //insert data into tables

        return true;
    }

    private function add_anonymous_user() {

        $uid = \DB::table('codo_users')->insertGetId(array(
            'username' => 'anonymous',
            'name' => 'Anonymous',
            'pass' => 'youJustCantCrackThis',
            'token' => '',
            'mail' => 'anonymous@localhost',
            'created' => time(),
            'last_access' => time(),
            'read_time' => 0,
            'user_status' => 0,
            'avatar' => '',
            'signature' => '',
            'no_posts' => 0,
            'profile_views' => 0,
            'oauth_id' => 0
        ));
        \DB::table('codo_user_roles')->insert(array(
            'uid' => $uid,
            'rid' => 1,
            'is_primary' => 1
        ));
    }

    public function install() {


        $capsule = new Capsule();
        \CODOF\Database\Schema::storeSchemaConnection($capsule);

        $this->create_tables();

        $dirs = array(
            'css', 'js', 'smarty', 'smarty/cache', 'smarty/templates_c', 'HB', 'HB/compiled',
        );

        foreach ($dirs as $dir) {

            $path = ABSPATH . 'cache/' . $dir;

            if (!is_dir($path)) {

                @mkdir($path);
                @chmod($path, 0777);
            }
        }

        $reg = new CODOF\User\Register(\DB::getPDO());
        $reg->username = $_POST['admin_user'];
        $reg->name = $reg->username;
        $reg->password = $_POST['admin_pass'];
        $reg->mail = $_POST['admin_mail'];
        $reg->user_status = 1;
        $reg->rid = ROLE_ADMIN;
        $reg->no_posts = 1;
        $reg->register_user();
        $reg->login();

        $this->add_anonymous_user();

        $permission = new \CODOF\Permission\Permission();

        $permission->add('view category', 'forum', 1, false);

        $permission->add('move topics', 'forum', array(
            ROLE_MODERATOR, ROLE_ADMIN
                ), false);

        $permission->add('moderate topics', 'forum', array(
            ROLE_MODERATOR, ROLE_ADMIN
                ), false);

        $permission->add('moderate posts', 'forum', array(
            ROLE_MODERATOR, ROLE_ADMIN
                ), false);

        $permission->add('make sticky', 'forum', array(
            ROLE_MODERATOR, ROLE_ADMIN
                ), true);

        $permission->add('edit profile', 'general', array(
            ROLE_ADMIN
        ));

        $permission->add('see history', 'forum', array(
            ROLE_USER, ROLE_MODERATOR, ROLE_ADMIN
        ));

        $permission->add('rep up', 'forum', array(
            ROLE_USER, ROLE_MODERATOR, ROLE_ADMIN
        ));

        $permission->add('rep down', 'forum', array(
            ROLE_USER, ROLE_MODERATOR, ROLE_ADMIN
        ));

        $permission->add('merge topics', 'forum', array(
            ROLE_MODERATOR, ROLE_ADMIN
        ));

        $permission->add('add tags', 'forum', array(
            ROLE_USER, ROLE_MODERATOR, ROLE_ADMIN
        ));

        $permission->add('close topics', 'forum', array(
            ROLE_MODERATOR, ROLE_ADMIN
        ));

        $permission->add('report topics', 'forum', array(
            ROLE_MODERATOR, ROLE_ADMIN
        ));

        $permission->add('move topics', 'forum', array(
            ROLE_MODERATOR, ROLE_ADMIN
        ));

        $permission->add('move posts', 'forum', array(
            ROLE_MODERATOR, ROLE_ADMIN
        ));

        $permission->add('add poll', 'forum', array(
            ROLE_USER, ROLE_MODERATOR, ROLE_ADMIN
        ));


        //set crons
        $cron = new \CODOF\Cron\Cron();
        $cron->set('daily_digest', 3600 * 24, 'now');
        $cron->set('weekly_digest', 3600 * 24 * 7, 'now');
        $cron->set('mail_notify_send', 1800, 'now');
        $cron->set('forum_update', 3600, 'now');
        $cron->set('badge_notify', 300, 'now');
        $cron->set('reload_license_info', 3600 * 24, 'now');

        //set as installed
        $filename = ABSPATH . "sites/default/config.php";
        $contents = file_get_contents($filename);
        $contents = str_replace("\$CF_installed=false;", "\$CF_installed=true;", $contents);
        file_put_contents($filename, $contents);
    }

}

$step3 = new Step3();

if (isset($_POST['db_name']) && isset($_POST['post_req'])) {

    $connected = $step3->connect_db();

    if ($connected == 'success') {

        $step3->write_conf();
    }

    echo $connected;
} else {

    $step3->install();
}

