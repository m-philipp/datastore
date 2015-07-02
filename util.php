<?php

/**
 * Created by PhpStorm.
 * User: martin
 * Date: 29.06.2015
 * Time: 04:16
 */
class Util
{
    public static function initNavigation($active = '')
    {
        if (getSession()->get(Session::LOGGED_IN) == true) {
            $ln = file_get_contents("./navigation/private-left.json");
            $rn = file_get_contents("./navigation/private-right.json");
        } else {
            $ln = file_get_contents("./navigation/public-left.json");
            $rn = file_get_contents("./navigation/public-right.json");
        }


        $navigation = json_decode($ln, true);
        $navigationRight = json_decode($rn, true);


        $params = array();
        $params['bp'] = getConfig()->get('global')->basepath;
        $params['navigationActive'] = $active;


        $params['navigation'] = $navigation;
        $params['navigationRight'] = $navigationRight;

        $params['navigationTemplate'] = getTemplate()->get('navigation.php', $params);
        $params['footer'] = getTemplate()->get('footer.php');

        return $params;
    }

    public static function checkLogin()
    {
        if (!getSession()->get(Session::LOGGED_IN)) {
            getRoute()->redirect(getConfig()->get('global')->basepath . 'error', null, true);
            return;
        }
    }

}