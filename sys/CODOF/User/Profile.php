<?php

/*
 * @CODOLICENSE
 */

namespace CODOF\User;

class profile {

    public function get_uid($id) {

        $uid = $id;

        if (!isset($_SESSION[UID . 'USER']['id']) && !$id) {

            //not passed id and not logged in
            header('Location: ' . User::getLoginUrl());
            exit;
        } else if (isset($_SESSION[UID . 'USER']['id']) && !$id) {

            //not passed id but is logged in so he is checking his own profile
            $uid = intval($_SESSION[UID . 'USER']['id']);
        } else {

            //passed id and may or not be logged in i.e checking someone else's
            //profile
            $user = \DB::table(PREFIX . 'codo_users')
                    ->where('id', '=', $id)
                    ->orWhere('username', '=', $id)
                    ->first();
            $uid = $user->id;

        }

        return $uid;
    }

}
