<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\HB;

use CODOF\Util;
use LightnCandy\LightnCandy;

class Render
{

    /**
     * Gets Handlebars template for required page
     * @param string $tpl
     * @return string
     */
    public static function get_template_contents($tpl, $isPlugin = false)
    {
        if ($isPlugin != false) {

            $path = PLUGIN_DIR . $isPlugin . '/' . $tpl;
        } else {

            $themes = Util::getThemesByInhereitance();
            foreach ($themes as $theme) {
                $path = THEME_DIR . "$theme/templates/$tpl.html";
                if (file_exists($path)) {
                    break;
                }
            }
        }
        return file_get_contents($path);
    }

    /**
     * Get data for building DOM from Handlebars template
     * @param string $tpl
     * @return array
     */
    public static function get_template_data($tpl)
    {

        $i18ns = array("find topics tagged", "new", "new replies", "replies", "views", "posted", "read more", "recent by", "Edit", "Delete", "Mark as spam");

        switch ($tpl) {

            case 'forum/topics' :
                $i18ns[] = "new topic";
                $i18ns[] = "Report";
                break;
            case 'forum/category' :
                break;
            case 'forum/topic' :
                $i18ns[] = 'reply';
                $i18ns[] = 'edited';
                $i18ns[] = 'Quote post';
                $i18ns[] = 'History';
                $i18ns[] = 'vote up';
                $i18ns[] = 'vote down';
                $i18ns[] = 'reputation points';
                break;
            case 'moderation/queue':
                $i18ns[] = 'Approve';
                $i18ns[] = 'Delete';
        }

        $trans = array();
        foreach ($i18ns as $i18n) {

            $trans[$i18n] = _t($i18n);
        }


        return array(
            "const" => self::get_required_constants(),
            "i18n" => $trans
        );
    }

    /**
     * Get required constants for javascript
     * @return array
     */
    public static function get_required_constants()
    {

        return array(
            "RURI" => RURI,
            "DURI" => DURI,
            "CAT_IMGS" => CAT_IMGS,
            "CAT_ICON_IMGS" => CAT_ICON_IMGS,
            "CURR_THEME" => CURR_THEME,
            "DEF_THEME_PATH" => DEF_THEME_PATH,
            "BADGES_PATH" => BADGES_PATH
        );
    }


    /**
     * Generate HTML from Handlebars template
     * @param string $tpl
     * @param array $data
     */
    public static function tpl($tpl, $data, $isPlugin = false)
    {

        $raw = self::get_template_contents($tpl, $isPlugin);
        $hash = md5($raw);

        $cachedPath = ABSPATH . 'cache/HB/compiled/' . $hash . '.php';

        //if (!file_exists($cachedPath)) {
            $contents = LightnCandy::compile($raw, array(
                'flags' => LightnCandy::FLAG_ERROR_LOG | LightnCandy::FLAG_STANDALONEPHP | LightnCandy::FLAG_HANDLEBARS,
                "helpers" => array(
                    "const" => function ($args) {
                        //single argument call
                        return constant($args);
                    },
                    "i18n" => function ($args) {

                        return _t($args);
                    },
                    "hide" => function ($args) {
                        return "";
                    },
                    "ifEquals" => function($arg1, $arg2, $options) {
                        if ($arg1 == $arg2) {
                            return $options['fn']();
                        } else {
                            return $options['inverse']();
                        }
                    }
                )
            ));

            file_put_contents($cachedPath, "<?php \n" . $contents);
       // }

        $renderer = include $cachedPath;
        return $renderer($data);
    }

}
