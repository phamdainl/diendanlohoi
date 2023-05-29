<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\Booter;

require 'Container.php';

class Load extends Container
{

    public function __construct()
    {
        spl_autoload_register(array($this, 'loader'));
        $aliaser = new AliasLoader();
        $aliaser->register();
    }

    /**
     *
     * Codoforum autoloader
     * @param string $class
     */
    public function loader($class)
    {
        // TODO: Restructure classes so that everything is under one namespace. Controller and Ext is wrong!
        if (strpos($class, "CODOF") === FALSE
            && strpos($class, "Controller") === FALSE
            && strpos($class, "Ext") === FALSE) {
            return;
        }

        $className = explode('\\', $class);

        $class = array_pop($className);
        $namespace = implode("/", $className);

        $file = ABSPATH . "sys/" . $namespace . "/" . $class . '.php';

        if (is_file($file)) {
            require_once $file;
        } else {
            echo 'Unable to require ' . $file;
        }
    }


}

new Load();