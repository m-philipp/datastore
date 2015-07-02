<?php

/**
 * Created by PhpStorm.
 * User: martin
 * Date: 02.07.2015
 * Time: 20:30
 */
Class Tokens
{

    public static function viewTokens()
    {
        Util::checkLogin();

        $sql = 'SELECT comment, token, validFrom, validTo, r, w FROM tokens WHERE uid=:uid';
        $tokens = getDatabase()->all($sql, array('uid' => getSession()->get(Session::USER_ID)));


        $params = Util::initNavigation('./tokens');
        $params['tokens'] = $tokens;
        $params['content'] = getTemplate()->get('tokens.php', $params);
        getTemplate()->display('layout.php', $params);
    }

    public static function addToken()
    {
        Util::checkLogin();

        if (!empty($_POST['validFrom']) && !empty($_POST['validTo'])) {

            $token = hash("sha512", Api::randomString(20));;

            $param = array(
                ":uid" => getSession()->get(Session::USER_ID),
                ":comment" => empty($_POST['comment']) ? "-" : $_POST['comment'],
                ':token' => $token,
                ":validFrom" => strtotime($_POST['validFrom']),
                ":validTo" => strtotime($_POST['validTo']),
                ":r" => !empty($_POST['r']) ? 1 : 0,
                ":w" => !empty($_POST['w']) ? 1 : 0
            );

            getDatabase()->execute('INSERT INTO tokens(uid, comment, token, validFrom, validTo, r, w) VALUES(:uid, :comment, :token, :validFrom, :validTo, :r, :w)', $param);


            getRoute()->redirect(getConfig()->get('global')->basepath . 'tokens', null, true);


        }
    }


    public static function deleteToken($token)
    {
        Util::checkLogin();

        echo $token;
        getDatabase()->execute('DELETE FROM tokens WHERE token=:token', array(':token' => $token));

        getRoute()->redirect(getConfig()->get('global')->basepath . 'tokens', null, true);
    }

}