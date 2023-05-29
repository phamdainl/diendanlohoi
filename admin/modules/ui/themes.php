<?php

/*
 * @CODOLICENSE
 */

$smarty = \CODOF\Smarty\Single::get_instance();

$db = \DB::getPDO();

if (isset($_POST['theme']) && CODOF\Access\CSRF::valid($_POST['CSRF_token'])) {

    $theme = $_POST['theme'];

    $query = "UPDATE " . PREFIX . "codo_config SET option_value=:value WHERE option_name=:key";
    $ps = $db->prepare($query);
    $ps->execute(array(':key' => 'theme', ':value' => $theme));

    $path = DATA_PATH . 'themes/' . $theme . '/info.php';
    \CODOF\Util::set_opt('forum_type', 'modern');
    if (\CODOF\Util::isPathInBaseDir($path, DATA_PATH)) {
        include $path;
        if (isset($info['forum_type']) && $info['forum_type'] == 'classic') {
            \CODOF\Util::set_opt('forum_type', 'classic');
        }
    }
}

CODOF\Util::get_config($db, true);

$files = array();
if ($handle = opendir(THEME_DIR)) {

    $i = 0;
    $curr_theme = CODOF\Util::get_opt('theme');
    while (false !== ($entry = readdir($handle))) {

        if ($entry != "." && $entry != ".." && $entry != "index.html" && $entry != "default" && $entry != ".DS_Store") {

            $entry = str_replace(".php", "", $entry);

            include DATA_PATH . 'themes/' . $entry . '/info.php';

            if ($curr_theme == $entry) {

                $files[$i]['active'] = true;
            } else {
                $files[$i]['active'] = false;
            }

            $files[$i]['name'] = $entry;
            $files[$i]['description'] = $info['description'];
            $files[$i]['thumb'] = A_DURI . 'themes/' . $entry . '/thumbnail.png';

            $i++;
        }
    }

    closedir($handle);
}

$smarty->assign('themes', $files);


$content = $smarty->fetch('ui/themes.tpl');