<?php

/*
 * @CODOLICENSE
 */

class Permission
{
    //private $dir = "sites/default/";
    public $permission_error = false;
    public $permits = array();

    public function __construct()
    {
        //Remember below files are used in builder too!
        $this->files = array("sites/default/config.php");
        $this->dirs = array("sites/default/assets/img/attachments/", "sites/default/assets/img/attachments/preview", "sites/default/assets/img/cats/", "sites/default/assets/img/cats/icons/",
            "sites/default/assets/img/profiles/", "sites/default/assets/img/profiles/icons/", "sites/default/assets/img/smileys/", "sites/default/assets/img/badges/", "cache/");
    }

    private function ret_is_writable($file)
    {
        $path = ABSPATH . $file;

        if (is_writable($path)) {

            return TRUE;
        }

        $this->permission_error = true;
        return FALSE;
    }

    private function set_permits($file)
    {
        $this->permits[] = array(
            "name" => $file,
            "perm" => $this->ret_is_writable($file)
        );
    }

    private function check_file_permissions()
    {
        foreach ($this->files as $file) {

            $this->set_permits($file);
        }
    }

    private function check_folder_permissions()
    {
        foreach ($this->dirs as $file) {
            if (!is_dir(ABSPATH . $file))
                @mkdir(ABSPATH . $file); //Fail silently if no permissions
            $this->set_permits($file);
        }
    }

    public function get_permits()
    {
        $this->check_file_permissions();
        $this->check_folder_permissions();

        return $this->permits;
    }

}

$perms = new Permission();
$permits = $perms->get_permits();

//$smarty->assign("permits", $permits);
$permission_error = $perms->permission_error;
