<?php

$smarty = \CODOF\Smarty\Single::get_instance();

$db = \DB::getPDO();

$rolesRecords = \DB::table(PREFIX . 'codo_roles')->get();
$roles = \CODOF\Util::toAssociativeArray($rolesRecords);

if (isset($_GET['action']) && CODOF\Access\CSRF::valid($_POST['CSRF_token'])) {

    if ($_GET['action'] == 'add') {

        \DB::table(PREFIX . 'codo_promotion_rules')
                ->insert(array(
                    'reputation' => $_POST['reputation'],
                    'posts' => $_POST['posts'],
                    'type' => $_POST['type'],
                    'rid' => $_POST['role']
        ));
    } else if ($_GET['action'] == 'edit') {


        \DB::table(PREFIX . 'codo_promotion_rules')
                ->where('id', $_POST['ruleid'])
                ->update(array(
                    'reputation' => $_POST['reputation'],
                    'posts' => $_POST['posts'],
                    'type' => $_POST['type'],
                    'rid' => $_POST['role']
        ));
    } else if ($_GET['action'] == 'delete') {

        \DB::table(PREFIX . 'codo_promotion_rules')
                ->where('id', $_POST['ruleid'])
                ->delete();
    }

    header("Location: index.php?page=reputation/promotions");
}

$rulesRecords = \DB::table(PREFIX . 'codo_promotion_rules AS p')
        ->leftJoin(PREFIX . 'codo_roles AS r', 'p.rid', '=', 'r.rid')
        ->get();

$rules = \CODOF\Util::toAssociativeArray($rulesRecords);
$smarty->assign('groups', $roles);
$smarty->assign('rules', $rules);

CODOF\Util::get_config($db);
$content = $smarty->fetch('reputation/promotions.tpl');
