<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\Forum;

class Category extends Forum
{

    protected $db;
    public static $child_ids = array();

    public function __construct($storage = false)
    {

        $this->db = $storage;
    }

    public static function get_alias($name)
    {

        return \CODOF\Filter::URL_safe($name);
    }

    /**
     * Fetches all categories information from codo_categories table     *
     * @return array
     */
    public function get_categories()
    {

        $cats = array();
        $user = \CODOF\User\User::get();
        $rids = implode(",", $user->rids);
        $qry = 'SELECT cat_id, cat_pid, cat_name, cat_alias, cat_img, no_topics, is_label,granted, show_children'
            . ' FROM ' . PREFIX . 'codo_categories, codo_permissions '
            . ' WHERE permission=\'view all topics\' AND cid=cat_id AND rid IN (' . $rids . ')'
            . ' AND EXISTS (SELECT 1 FROM codo_permissions AS p WHERE '
            . '  p.cid=cat_id AND p.rid IN (' . $rids . ') AND permission=\'view category\' AND granted=1) '
            . ' ORDER BY cat_order';

        $ans = $this->db->query($qry);

        if ($ans) {

            $cats = $ans->fetchAll(\PDO::FETCH_CLASS);
        }

        $cats = \CODOF\Hook::call('on_get_categories', $cats);

        return $cats;
    }

    public function getCategoriesForClassicView($new_topics)
    {
        $cats = array();
        $user = \CODOF\User\User::get();
        $qry = 'SELECT cat_id, cat_pid, cat_name, cat_alias, cat_img, is_label,granted, show_children,'
            . ' cat_description, no_topics, no_posts'
            . ' FROM ' . PREFIX . 'codo_categories, codo_permissions '
            . ' WHERE permission=\'view all topics\' AND cid=cat_id AND rid=' . $user->rid . ''
            . ' AND EXISTS (SELECT 1 FROM codo_permissions AS p WHERE '
            . '  p.cid=cat_id AND p.rid=' . $user->rid . ' AND permission=\'view category\' AND granted=1) '
            . ' ORDER BY cat_pid, cat_order';

        $ans = $this->db->query($qry);
        if ($ans) {
            $cats = $ans->fetchAll(\PDO::FETCH_CLASS);
        }

        $catIds = [];
        foreach ($cats as $cat) {
            $catIds[] = $cat->cat_id;
        }

        $qry = 'SELECT t.cat_id, t.last_post_name AS last_poster_name, t.last_post_uid AS luid, t.uid as tuid, '
            . ' u.name AS topic_creator_name, t.last_post_time AS lpost_time, t.topic_id, t.title, '
            . '   r.rname AS t_rname, pr.rname AS p_rname '
            . '  FROM ' . PREFIX . 'codo_topics t '
            . '  INNER JOIN (SELECT cat_id, MAX(last_post_time) last_post_time FROM ' . PREFIX . 'codo_topics 
                            WHERE topic_status <> ' . Forum::DELETED . ' GROUP BY cat_id) tmp '
            . '    ON t.cat_id = tmp.cat_id AND t.last_post_time=tmp.last_post_time '
            . '  LEFT JOIN ' . PREFIX . 'codo_users u ON u.id=t.uid '
            . '  LEFT JOIN ' . PREFIX . 'codo_user_roles ur ON ur.is_primary=1 AND ur.uid=u.id '
            . '  LEFT JOIN ' . PREFIX . 'codo_roles r ON r.rid=ur.rid '
            . '  LEFT JOIN ' . PREFIX . 'codo_user_roles pur ON pur.is_primary=1 AND pur.uid=t.last_post_uid '
            . '  LEFT JOIN ' . PREFIX . 'codo_roles pr ON pr.rid=pur.rid'
            . '  WHERE t.topic_status <> ' . Forum::DELETED;

        $res = $this->db->query($qry);

        // TODO: Why is name saved in codo_topics, fetch it from codo_users using a join instead
        if ($res) {
            $latestTopics = $res->fetchAll(\PDO::FETCH_CLASS);
            $latestTopicData = [];
            foreach ($latestTopics as $latestTopic) {
                $uid = $latestTopic->luid;
                $name = $latestTopic->last_poster_name;
                $rname = $latestTopic->p_rname;
                if ($uid == null || $name == null || $rname == null) {
                    $uid = $latestTopic->tuid;
                    $name = $latestTopic->topic_creator_name;
                    $rname = $latestTopic->t_rname;
                }

                $latestTopicData[$latestTopic->cat_id] = array(
                    "uid" => $uid,
                    "name" => $name,
                    "time" => $latestTopic->lpost_time,
                    "topic_id" => $latestTopic->topic_id,
                    "title" => $latestTopic->title,
                    "rname" => $rname
                );
            }
        }

        foreach ($cats as $cat) {
            if (isset($latestTopicData[$cat->cat_id])) {
                $cat->latestTopicData = $latestTopicData[$cat->cat_id];
            }

            if (isset($new_topics[$cat->cat_id]) && $new_topics[$cat->cat_id] > 0) {
                $cat->numNewTopics = $new_topics[$cat->cat_id];
            }
        }

        return \CODOF\Hook::call('on_get_categories', $cats);
    }

    public function getCategoriesWhereUserCanCreateTopic()
    {

        $user = \CODOF\User\User::get();
        $rids = implode(",", $user->rids);

        $qry = 'SELECT cat_id, cat_pid, cat_name, cat_alias, no_topics, cat_img'
            . ' FROM ' . PREFIX . 'codo_categories'
            . ' WHERE EXISTS ('
            . '     SELECT 1 FROM ' . PREFIX . 'codo_permissions c, ' . PREFIX . 'codo_permissions c2 '
            . '           WHERE'
            . '           c.permission=\'create new topic\'  AND c.granted=1'
            . '                 AND c.cid=cat_id AND c.rid IN (' . $rids . ') AND'
            . '               c2.permission=\'view category\'  AND c2.granted=1'
            . '             AND c2.cid=cat_id AND c2.rid IN (' . $rids . ')'
            . '   )        '
            . ' ORDER BY cat_order';

        $ans = $this->db->query($qry);

        if ($ans) {
            $cats = $ans->fetchAll(\PDO::FETCH_CLASS);
        }

        $cats = \CODOF\Hook::call('on_get_categories_for_create_topic', $cats);

        return $cats;
    }

    public function exists($cid)
    {

        $qry = 'SELECT COUNT(cat_id) FROM ' . PREFIX . 'codo_categories WHERE cat_id = ' . $cid;
        $res = $this->db->query($qry);

        if ($res->fetchColumn() == 0) {

            return FALSE;
        }

        return TRUE;
    }

    /**
     *
     * Fetches ctaegory from given cat_alias
     * @param string $cat_alias
     * @return array
     */
    public function get_cat_info($cat_alias)
    {

        //$t = microtime(true);
        $qry = 'SELECT cat_id, cat_name, cat_description, cat_img, no_topics, no_posts,default_subscription_type FROM ' . PREFIX . 'codo_categories '
            . ' WHERE cat_alias=:cat_alias LIMIT 1';

        $stmt = $this->db->prepare($qry);
        $ans = $stmt->execute(array(":cat_alias" => $cat_alias));

        if ($ans) {

            $cat_info = $stmt->fetch();
        }
        //echo " <br/>get_cat_info() ";
        //echo microtime(true) - $t;

        return $cat_info;
    }

    /**
     *
     * Returns total number of topics in all categories
     *
     * @return int No. of topics
     */
    public function get_total_num_topics($cat_id)
    {
        $qry = "SELECT COUNT(t.topic_id) AS total_num_topics 
                FROM " . PREFIX . "codo_topics AS t" . " 
                WHERE t.cat_id=$cat_id AND t.topic_status <> 0 AND "
            . $this->getViewTopicPermissionConditions(true);

        $obj = $this->db->query($qry);
        $res = $obj->fetch();

        return $res ['total_num_topics'];
    }


    public function get_sub_categories($cats_tree, $pid)
    {
        $res = null;
        $this->get_this_cat($cats_tree, $pid, $res);
        return $res;
    }

    public function find_parents($cats, $cid)
    {
        $eff_arr = array();

        foreach ($cats as $cat) {

            $eff_arr[$cat->cat_id] = $cat;
        }

        $parents = array();
        while (($cid = $eff_arr[$cid]->cat_pid) != 0) {

            $parents[] = array(
                "name" => $eff_arr[$cid]->cat_name,
                "alias" => $eff_arr[$cid]->cat_alias
            );
        }

        return array_reverse($parents);
    }

    /**
     * Gets the name of the category of passed id
     * @param <array> $id
     */
    public function get_cat_names_by_id($ids)
    {

        $q_ids = implode(',', $ids);
        $qry = 'SELECT cat_name,cat_id FROM ' . PREFIX . 'codo_categories WHERE cat_id IN (' . $q_ids . ')';
        $res = $this->db->query($qry);

        $cat_names = $res->fetchAll();

        return $cat_names;
    }

    /** private functions --------------------------------------------------------* */
    private function get_this_cat($cats, $pid, &$res)
    {
        foreach ($cats as $cat) {
            if ($cat->cat_id == $pid) {
                if (property_exists($cat, "children")) {
                    $res = $cat->children;
                } else {
                    $res = [];
                }
                break;
            } else if (property_exists($cat, 'children')) {
                $this->get_this_cat($cat->children, $pid, $res);
            }
        }
    }

    /**
     * Updates the last post details from all children of first level categories
     * @param $cats
     * @return  \stdClass[]
     */
    public function updateLatestTopicData($cats)
    {
        foreach ($cats as $cat) {
            if (property_exists($cat, 'children')) {
                $this->updateTopicDataRecursively($cat, $cat->children);
            }
        }
        return $cats;
    }

    private function updateTopicDataRecursively($firstLevelCat, $cats)
    {
        foreach ($cats as $cat) {
            if ($firstLevelCat != null && property_exists($cat, 'latestTopicData')
                && $cat->latestTopicData['time'] > $firstLevelCat->latestTopicData['time']) {
                $firstLevelCat->latestTopicData = $cat->latestTopicData;
            }
            if (property_exists($cat, 'children')) {
                $this->updateTopicDataRecursively($firstLevelCat, $cat->children);
            }
        }
    }
}