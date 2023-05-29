<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\Forum;

use CODOF\Util;

class Forum
{

    public $pid;

    const DELETED = 0;
    const APPROVED = 1;
    const APPROVED_CLOSED = 2;
    const MODERATION_BY_FILTER = 3;
    const MODERATION_BY_USER = 4;
    const PRE_MODERATION = 5;
    const STICKY = 6;
    const STICKY_ONLY_CATEGORY = 7;
    const STICKY_CLOSED = 8;
    const STICKY_ONLY_CATEGORY_CLOSED = 9;
    const MERGED_REDIRECT_ONLY = 10;

    static function isSticky($status)
    {

        return in_array((int)$status, array(Forum::STICKY, Forum::STICKY_CLOSED, Forum::STICKY_ONLY_CATEGORY, Forum::STICKY_ONLY_CATEGORY_CLOSED));
    }

    static function isClosed($status)
    {

        return in_array((int)$status, array(Forum::APPROVED_CLOSED, Forum::STICKY_CLOSED, Forum::STICKY_ONLY_CATEGORY_CLOSED));
    }

    public function update()
    {

        $db = \DB::getPDO();
        //set topic status as closed for auto_close topics
        $qry = 'UPDATE ' . PREFIX . 'codo_topics SET topic_status = CASE '
            . '  WHEN topic_status=' . Forum::DELETED . ' THEN ' . Forum::DELETED
            . '  WHEN topic_status=' . Forum::APPROVED . ' THEN ' . Forum::APPROVED_CLOSED
            . '  WHEN topic_status=' . Forum::STICKY . ' THEN ' . Forum::STICKY_CLOSED
            . '  WHEN topic_status=' . Forum::STICKY_ONLY_CATEGORY . ' THEN ' . Forum::STICKY_ONLY_CATEGORY_CLOSED
            . '  ELSE topic_status '
            . ' END '
            . ' WHERE topic_close > 0 AND topic_close < ' . time();


        $db->query($qry);
    }

    /**
     * Generates a parent child object tree from a linear array
     * @param array $res
     * @param int $pid
     * @param \stdClass $tree
     * @return \stdClass
     */
    function generate_tree($res, $pid = 0, $tree = null)
    {

        if ($tree == null) {
            $tree = new \stdClass();
        }

        foreach ($res as $r) {
            if ($r->cat_pid == $pid) {

                if ($r->cat_pid == 0) {
                    $tree->{$r->cat_id} = $this->generate_tree($res, $r->cat_id, $r);
                } else {

                    if (!property_exists($tree, 'children')) {

                        $tree->{'children'} = new \stdClass();
                    }
                    $tree->{'children'}->{$r->cat_id} = $this->generate_tree($res, $r->cat_id, $r);
                }
            }
        }
        //var_dump($tree);
        return $tree;
    }

    function expandTree($catTree, &$parentNode, $secondLevelNode)
    {
        $parentNode->children = [];
        foreach ($catTree[$parentNode->cat_id] as $node) {
            if ($secondLevelNode != null && property_exists($node, 'latestTopicData')
                && $node->latestTopicData['time'] > $secondLevelNode->latestTopicData['time']) {
                $secondLevelNode->latestTopicData = $node->latestTopicData;
            }
            $parentNode->children[] = $node;
            if (isset($catTree[$node->cat_id])) {
                $this->expandTree($catTree, $node, $secondLevelNode == null ? $node : $secondLevelNode);
            }
        }
    }

    /**
     * Since, we are restricting our UI to second level nesting, the below nesting level is hardcoded
     * If we want to support any level of nesting a recursive function would be more suitable but given
     * this use-case, below code would perform much more efficiently than its recursive counterpart.
     * TODO: Make it dynamic and n as an argument
     * @param $cats
     * @return array
     */
    function generateNestedCategories($cats)
    {
        $catTree = [];
        foreach ($cats as $cat) {
            if (!isset($catTree[$cat->cat_pid])) {
                $catTree[$cat->cat_pid] = [];
            }
            $catTree[$cat->cat_pid][] = $cat;
        }

        foreach ($catTree[0] as $node) {
            if (isset($catTree[$node->cat_id])) {
                $secondLevelNode = null; // we are still at first level so we dont know second level cats yet
                $this->expandTree($catTree, $node, $secondLevelNode);
            }
        }

        return $catTree[0];
    }

    /**
     *
     * Recursively adds no_topics of sub categories to parent categories
     * @param array $cats
     * @return int
     */
    function update_count(&$cats)
    {

        $cnt = 0;

        foreach ($cats as $c) {

            $c->no_topics = (int)$c->no_topics + $this->update_count($c->children);
            $cnt += $c->no_topics;
        }

        if ($cats != null) {

            //total count of children of this category
            return $cnt;
        }

        //no children so 0
        return 0;
    }

    public function get_js_editor_files()
    {

        $files = array('markitup/jquery.markitup.js', 'markitup/parsers/marked.js',
            'markitup/highlight/highlight.pack.js', 'dropzone/dropzone.js',
            'js/editor.js', 'js/fittext.js', 'js/griphandler.js', 'oembedget/oembed-get.js',
            'js/jquery.textcomplete.js');

        $assets = array();
        foreach ($files as $file) {

            $assets[] = array(DATA_PATH . 'assets/' . $file, array('type' => 'defer'));
        }

        return $assets;
    }

    /**
     * Gets js files as asset array that is required to load oembed
     * @return array
     */
    public function getOEmbedJsAssets()
    {

        $files = array('js/editor.js', 'oembedget/oembed-get.js');

        $assets = array();
        foreach ($files as $file) {
            $assets[] = array(DATA_PATH . 'assets/' . $file, array('type' => 'defer'));
        }

        return $assets;
    }

    /**
     * Generates pagination html
     * @param int $num_pages
     * @param int $curr_page
     * @param string $url
     * @param bool $root
     * @param array $search_data
     * @return string
     */
    public function paginate($num_pages, $curr_page, $url, $root = false, $search_data = array())
    {

        $html = '';
        $times = 5 + ($curr_page - 2);

        if ($num_pages < $times) {

            $times = $num_pages; //run num times
        }

        if (!$root) {

            $url = RURI . $url;
        }

        $search_str = "";
        if (!empty($search_data)) {
            if (is_array($search_data))
                $search_str = "&str=" . $search_data['str'];
            else
                $search_str .= $search_data;
        }

        $cnt = 1;

        if ($curr_page > 5) {

            $html .= '<a href="' . $url . $cnt . $search_str . '"> ' . $cnt . '</a> ... ';
            $cnt += ($curr_page - 4);
        }

        for ($i = $cnt; $i <= $times; $i++) {

            if ($curr_page == $i) {
                $html .= '<a class="codo_topics_curr_page">' . $i . '</a>';
            } else {
                $html .= '<a href="' . $url . $i . $search_str . '">' . $i . '</a>';
            }
        }

        if ($num_pages > $times) {
            $html .= ' ... <a href="' . $url . $num_pages . $search_str . '">' . $num_pages . '</a>';
        }

        return $html;
    }

    /**
     * Returns the total no of pages
     * @param int $total
     * @param int $per_page
     * @return int
     */
    public function get_num_pages($total, $per_page)
    {
        return ceil($total / $per_page);
    }

    /**
     * Generates absolute url to topic or post[if post id provided]
     * @param int $tid topic id
     * @param string $title topic title
     * @param int $pid post id [optional]
     * @return string absolute url to topic/post
     */
    public static function getPostURL($tid, $title, $pid = false)
    {

        $safe_title = \CODOF\Filter::URL_safe($title);

        $url = RURI . "topic/$tid/$safe_title";

        if ($pid) {

            $url .= "/post-$pid/#post-$pid";
        }
        return $url;
    }

    /**
     * Returns true if user can create topic in atleast one category
     * @return boolean
     */
    public function canCreateTopicInAtleastOne()
    {

        $user = \CODOF\User\User::get();
        $count = \DB::table(PREFIX . 'codo_permissions')
            ->where('permission', '=', 'create new topic')
            ->where('granted', '=', 1)
            ->whereIn('rid', $user->rids)
            ->groupBy('permission')
            ->count();

        return $count > 0;
    }

    /**
     * Returns true if topic can be created in the specified category
     * @param int $cid
     * @return boolean
     */
    public function canCreateTopicIn($cid)
    {

        $user = \CODOF\User\User::get();

        return $user->can('create new topic', $cid);
    }

    public function getCatNameFromId($cid)
    {

        return
            \DB::table(PREFIX . 'codo_categories')->where('cat_id', $cid)->pluck('cat_name');
    }

    protected function topicInModeration($alias = '')
    {

        if ($alias != '') {

            return ' (' . $alias . '.topic_status=2 OR ' . $alias . '.topic_status=3) ';
        }
        return ' (topic_status=2 OR topic_status=3) ';
    }

    protected function postInModeration($alias = '')
    {

        if ($alias != '') {

            return ' (' . $alias . '.post_status=2 OR ' . $alias . '.post_status=3) ';
        }
        return ' (post_status=2 OR post_status=3) ';
    }

    /**
     * Conditionns of SQL query that restrict users to view topics
     * based on user roles/groups assigned to them
     */
    protected function getPermissionConditions($permission, $alias = 't')
    {

        $user = \CODOF\User\User::get();
        $rids = implode(",", $user->rids);

        /**
         *
         * 0   0   view all topics  0
         * 0   0   view my  topics  1
         * 3   0   view all topics  1
         * 3   0   view my  topics  0
         *
         *
         */
        //NOTE: 'view my topics' & 'view all topics' are mutuall exclusive
        //      so they both cannot be set as granted at once.
        //TODO: Is topic level permission really required ?
        $conditions = ' '
            . 'EXISTS (SELECT 1 FROM codo_permissions AS permission  '
            . ' WHERE  permission.rid IN (' . $rids . ') '
            . ' AND '
            . '  ('
            . '    ('
            . '      permission.cid = ' . $alias . '.cat_id'
            . '      AND permission.tid=0 '
            . '    )'
            . '    OR '
            . '    permission.tid=' . $alias . '.topic_id'
            . '  ) '
            . ' AND permission.granted=1 '
            . ' AND '
            . '  ('
            . '    permission.permission=\'' . $permission . '\' OR '
            . '    (permission.permission=\'' . $permission . '\' AND ' . $alias . '.uid=' . $user->id . ') '
            . '  ) '
            . ' )';

        return $conditions;
    }

    /**
     * Checks if particular topic can be viewed by current user or not
     *
     * @param int $tuid
     *            topic creator's userid
     * @param int $cid
     * @param int $tid
     *
     * @return boolean
     */
    public static function canViewTopic($tuid, $cid, $tid)
    {
        $user = \CODOF\User\User::get();

        return $tuid == $user->id && $user->canAny(array(
                'view my topics',
                'view all topics'
            ), $cid, $tid) ||
            // my topic, check permission to view my or all topics
            $tuid != $user->id && $user->can('view all topics', $cid, $tid);
        // not my topic, check permission to view all topics
    }


    /**
     * Conditions of SQL query that restrict users to view topics
     * based on user roles/groups assigned to them
     */
    public function getViewTopicPermissionConditions($forCategory = false)
    {
        $user = \CODOF\User\User::get();
        $rids = implode(",", $user->rids);

        /**
         * 0 0 view all topics 0
         * 0 0 view my topics 1
         * 3 0 view all topics 1
         * 3 0 view my topics 0
         */
        // NOTE: 'view my topics' & 'view all topics' are mutually exclusive
        // so they both cannot be set as granted at once.
        // TODO: Is topic level permission really required ?
        $conditions = ' EXISTS (SELECT 1 FROM codo_permissions AS permission  ' .
            ' WHERE  permission.rid IN (' . $rids . ') ' . ' AND ' . '  (' . '    (' .
            '      permission.cid = t.cat_id' . '      AND permission.tid=0 ' . '    )' . '    OR ' .
            '    permission.tid=t.topic_id' . '  ) ' . ' AND permission.granted=1 ' . ' AND ' . '  (' .
            '    permission.permission=\'view all topics\' OR ' .
            '    (permission.permission=\'view my topics\' AND t.uid=' . $user->id . ') ' . '  ) ' . ' )';


        $conditions = " ( $conditions {$this->getShowStickyTopicsConditions($forCategory)} ) ";

        return $conditions;
    }


    /**
     * Sticky topics are sometimes informational topics that might be required to be displayed even if they
     * are not allowed in permissions. If this setting is set to  yes in backend, we will exclude these topics
     * This function returns a condition to achieve that
     * @param bool $includeStickyOnlyCategory
     * @return string
     */
    private function getShowStickyTopicsConditions($includeStickyOnlyCategory = true)
    {
        $showStickyCondition = '';
        $showStickyTopicsToAll = Util::get_opt("show_sticky_topics_without_permission") === "yes";

        if ($includeStickyOnlyCategory) {
            $statusArr = [Forum::STICKY, Forum::STICKY_CLOSED, Forum::STICKY_ONLY_CATEGORY, Forum::STICKY_ONLY_CATEGORY_CLOSED];
        } else {
            $statusArr = [Forum::STICKY, Forum::STICKY_CLOSED];
        }

        if ($showStickyTopicsToAll) {
            $showStickyCondition = ' OR (t.topic_status IN ( ' . join(",", $statusArr) . '))';
        }

        return $showStickyCondition;
    }

}
