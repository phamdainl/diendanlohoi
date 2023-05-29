<?php

/*
 * @CODOLICENSE
 */

if(version_compare(PHP_VERSION, '8.0') < 0){
    die('You are using an old unsupported version of PHP. Please upgrade to PHP 8.0.0 or above.');
}

define('IN_CODOF', 'installer');

error_reporting(E_ALL);

date_default_timezone_set('Europe/London');
require 'load.php';

global $CF_installed;

if (!isset($_REQUEST['step'])) {

    $step = 1;
} else {

    $step = (int) $_REQUEST['step'];
}

$already_installed = 'no';

if ($CF_installed) {

    if($step != 1) {

        header("Location: " . RURI . "index.php?step=1");
        exit;
    }
    $already_installed = 'yes';
}

require "step$step.php";

$url = str_replace('index.php?u=/', '', RURI);
define('HOME' , str_replace("install/", "", $url));

if (!isset($_POST['post_req'])) {
     require "templates/step$step.php";
}
