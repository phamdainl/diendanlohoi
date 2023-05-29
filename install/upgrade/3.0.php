<?php

DB::table(PREFIX . 'codo_config')
        ->where('option_name', 'version')
        ->update(array('option_value' => '3.0'));


if (\CODOF\Util::get_opt('version') == 3) {

    return;
}

/**
 * Table: codo_block_roles
 */
$block_r = DB::table(PREFIX . 'codo_block_roles')->get();
Schema::dropIfExists(PREFIX . 'codo_block_roles');
Schema::create(PREFIX . 'codo_block_roles', function($table) {
    $table->integer('bid');
    $table->integer('rid');
    $table->index('rid');
    $table->index('bid');
});
DB::table(PREFIX . 'codo_block_roles')->insert($block_r);

function build_arr($columns, $values) {

    $multi_value = array();

    foreach ($values as $value) {

        $multi_value[] = combine($columns, $value);
    }

    return $multi_value;
}

function combine($col, $row) {

    $arr = array();

    $i = 0;
    foreach ($row as $val) {

        $arr[$col[$i]] = $val;

        $i++;
    }

    return $arr;
}

/**
 * Table: codo_permissions
 */
Schema::dropIfExists(PREFIX . 'codo_permissions');
Schema::create('codo_permissions', function($table) {

    $table->increments('pid');
    $table->integer('rid')->index();
    $table->integer('cid');
    $table->integer('tid');
    $table->string('permission', 128);
    $table->integer('granted');
    $table->integer('inherited');
});

$columns = array('pid', 'cid', 'tid', 'rid', 'permission', 'granted', 'inherited');
$values = array(
    array(296, 0, 0, 1, 'view user profiles', 1, -1),
    array(297, 0, 0, 1, 'use search', 1, -1),
    array(298, 0, 0, 1, 'view all topics', 1, -1),
    array(299, 0, 0, 1, 'view my topics', 0, -1),
    array(300, 0, 0, 1, 'create new topic', 0, -1),
    array(301, 0, 0, 1, 'reply to all topics', 0, -1),
    array(302, 0, 0, 1, 'edit my topics', 0, -1),
    array(303, 0, 0, 1, 'edit all topics', 0, -1),
    array(304, 0, 0, 1, 'delete my topics', 0, -1),
    array(305, 0, 0, 1, 'delete all topics', 0, -1),
    array(306, 0, 0, 1, 'view forum', 1, -1),
    array(355, 0, 0, 1, 'edit my posts', 0, -1),
    array(356, 0, 0, 1, 'edit all posts', 0, -1),
    array(357, 0, 0, 1, 'delete my posts', 0, -1),
    array(358, 0, 0, 1, 'delete all posts', 0, -1),
    array(359, 0, 0, 2, 'view user profiles', 1, -1),
    array(360, 0, 0, 2, 'use search', 1, -1),
    array(361, 0, 0, 2, 'view all topics', 1, -1),
    array(362, 0, 0, 2, 'view my topics', 0, -1),
    array(363, 0, 0, 2, 'create new topic', 1, -1),
    array(364, 0, 0, 2, 'reply to all topics', 1, -1),
    array(365, 0, 0, 2, 'edit my topics', 1, -1),
    array(366, 0, 0, 2, 'edit all topics', 0, -1),
    array(367, 0, 0, 2, 'delete my topics', 1, -1),
    array(368, 0, 0, 2, 'delete all topics', 0, -1),
    array(369, 0, 0, 2, 'view forum', 0, -1),
    array(370, 0, 0, 2, 'edit my posts', 1, -1),
    array(371, 0, 0, 2, 'edit all posts', 0, -1),
    array(372, 0, 0, 2, 'delete my posts', 1, -1),
    array(373, 0, 0, 2, 'delete all posts', 0, -1),
    array(374, 0, 0, 3, 'view user profiles', 1, -1),
    array(375, 0, 0, 3, 'use search', 1, -1),
    array(376, 0, 0, 3, 'view all topics', 1, -1),
    array(377, 0, 0, 3, 'view my topics', 0, -1),
    array(378, 0, 0, 3, 'create new topic', 1, -1),
    array(379, 0, 0, 3, 'reply to all topics', 1, -1),
    array(380, 0, 0, 3, 'edit my topics', 1, -1),
    array(381, 0, 0, 3, 'edit all topics', 1, -1),
    array(382, 0, 0, 3, 'delete my topics', 1, -1),
    array(383, 0, 0, 3, 'delete all topics', 1, -1),
    array(384, 0, 0, 3, 'view forum', 0, -1),
    array(385, 0, 0, 3, 'edit my posts', 1, -1),
    array(386, 0, 0, 3, 'edit all posts', 1, -1),
    array(387, 0, 0, 3, 'delete my posts', 1, -1),
    array(388, 0, 0, 3, 'delete all posts', 1, -1),
    array(389, 0, 0, 4, 'view user profiles', 1, -1),
    array(390, 0, 0, 4, 'use search', 1, -1),
    array(391, 0, 0, 4, 'view all topics', 1, -1),
    array(392, 0, 0, 4, 'view my topics', 0, -1),
    array(393, 0, 0, 4, 'create new topic', 1, -1),
    array(394, 0, 0, 4, 'reply to all topics', 1, -1),
    array(395, 0, 0, 4, 'edit my topics', 1, -1),
    array(396, 0, 0, 4, 'edit all topics', 1, -1),
    array(397, 0, 0, 4, 'delete my topics', 1, -1),
    array(398, 0, 0, 4, 'delete all topics', 1, -1),
    array(399, 0, 0, 4, 'view forum', 0, -1),
    array(400, 0, 0, 4, 'edit my posts', 1, -1),
    array(401, 0, 0, 4, 'edit all posts', 1, -1),
    array(402, 0, 0, 4, 'delete my posts', 1, -1),
    array(403, 0, 0, 4, 'delete all posts', 1, -1),
    array(404, 0, 0, 6, 'view user profiles', 1, -1),
    array(405, 0, 0, 6, 'use search', 1, -1),
    array(406, 0, 0, 6, 'view all topics', 0, -1),
    array(407, 0, 0, 6, 'view my topics', 0, -1),
    array(408, 0, 0, 6, 'create new topic', 0, -1),
    array(409, 0, 0, 6, 'reply to all topics', 0, -1),
    array(410, 0, 0, 6, 'edit my topics', 0, -1),
    array(411, 0, 0, 6, 'edit all topics', 0, -1),
    array(412, 0, 0, 6, 'delete my topics', 0, -1),
    array(413, 0, 0, 6, 'delete all topics', 0, -1),
    array(414, 0, 0, 6, 'view forum', 0, -1),
    array(415, 0, 0, 6, 'edit my posts', 0, -1),
    array(416, 0, 0, 6, 'edit all posts', 0, -1),
    array(417, 0, 0, 6, 'delete my posts', 0, -1),
    array(418, 0, 0, 6, 'delete all posts', 0, -1),
    array(419, 0, 0, 5, 'view user profiles', 1, -1),
    array(420, 0, 0, 5, 'use search', 1, -1),
    array(421, 0, 0, 5, 'view all topics', 1, -1),
    array(422, 0, 0, 5, 'view my topics', 0, -1),
    array(423, 0, 0, 5, 'create new topic', 0, -1),
    array(424, 0, 0, 5, 'reply to all topics', 0, -1),
    array(425, 0, 0, 5, 'edit my topics', 0, -1),
    array(426, 0, 0, 5, 'edit all topics', 0, -1),
    array(427, 0, 0, 5, 'delete my topics', 0, -1),
    array(428, 0, 0, 5, 'delete all topics', 0, -1),
    array(429, 0, 0, 5, 'view forum', 1, -1),
    array(430, 0, 0, 5, 'edit my posts', 0, -1),
    array(431, 0, 0, 5, 'edit all posts', 0, -1),
    array(432, 0, 0, 5, 'delete my posts', 0, -1),
    array(433, 0, 0, 5, 'delete all posts', 0, -1)
);

$permissions = build_arr($columns, $values);

DB::table('codo_permissions')->insert(
        $permissions
);

/**
 * Table: codo_permission_list
 */
Schema::dropIfExists(PREFIX . 'codo_permission_list');
Schema::create('codo_permission_list', function($table) {

    $table->increments('id');
    $table->string('permission', 128);
    $table->string('type', 50);
});

$columns = array('id', 'permission', 'type');
$values = array(
    array(1, 'view user profiles', 'general'),
    array(2, 'use search', 'general'),
    array(3, 'view all topics', 'forum'),
    array(4, 'view my topics', 'forum'),
    array(5, 'create new topic', 'forum'),
    array(6, 'reply to all topics', 'forum'),
    array(7, 'edit my topics', 'forum'),
    array(8, 'edit all topics', 'forum'),
    array(9, 'delete my topics', 'forum'),
    array(10, 'delete all topics', 'forum'),
    array(11, 'view forum', 'general')
);

$permission_list = build_arr($columns, $values);

DB::table('codo_permission_list')->insert(
        $permission_list
);

$role = DB::table('codo_roles')
        ->where('rid', '=', 6)
        ->first();

if ($role == null) {
    DB::table('codo_roles')->insert(
            array('rid' => 6, 'rname' => 'banned')
    );
}

Schema::dropIfExists('codo_role_permissions');

/**
 * Table: codo_user_roles
 */

if (!\CODOF\Upgrade\Upgrade::tableExists('codo_user_roles')) {
    
    Schema::create('codo_user_roles', function($table) {

        $table->integer('uid');
        $table->integer('rid');
        $table->integer('is_primary');
        $table->primary(array('uid', 'rid'));
    });
}

$user = DB::table('codo_users')
        ->where('username', '=', 'anonymous')
        ->where('mail', '=', 'anonymous@localhost')
        ->first();

if ($user == null) {
    DB::table('codo_users')->insert(array(
        'username' => 'anonymous',
        'name' => 'Anonymous',
        'pass' => 'youJustCantCrackThis',
        'token' => '',
        'mail' => 'anonymous@localhost',
        'created' => time(),
        'last_access' => time(),
        'read_time' => 0,
        'rid' => 1,
        'user_status' => 0,
        'avatar' => '',
        'signature' => '',
        'no_posts' => 0,
        'profile_views' => 0,
        'oauth_id' => 0
    ));
}


if (\CODOF\Upgrade\Upgrade::columnExists('codo_users', 'rid')) {

    $users = DB::table('codo_users')->select('id', 'rid')->get();

    $user_roles = array();

    foreach ($users as $user) {

        $user_roles[] = array(
            'uid' => $user['id'],
            'rid' => $user['rid'],
            'is_primary' => 1
        );
    }

    DB::table('codo_user_roles')->insert($user_roles);

    Schema::table('codo_users', function($table) {
        $table->dropColumn('rid');
    });
}