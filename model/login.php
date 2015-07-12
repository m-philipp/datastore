<?php

/**
 * Created by PhpStorm.
 * User: martin
 * Date: 29.06.2015
 * Time: 05:32
 */
Class Login
{

    /**
     * Model showing the login page with a failure message.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function loginFailed()
    {
        self::generateLoginPage(true);
    }


    /**
     * Model showing the login page optional with a failed message.
     * @param bool $failed if a previous login attempt failed.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function generateLoginPage($failed = false)
    {

        $params = Util::initNavigation('./login');
        $params['loginFailed'] = $failed;
        $params['content'] = getTemplate()->get('login.php', $params);

        getTemplate()->display('layout.php', $params);
    }

    /**
     * Checking the POSTed credentials if correct performing a login and redirecting to start page.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function checkCredentials()
    {

        if (!isset($_POST['inputMail']) or !isset($_POST['inputPassword'])) {
            getRoute()->redirect(getConfig()->get('global')->basepath . 'login/failed', null, true);
            return;
        }


        $password = hash("sha512", getConfig()->get('global')->salt . $_POST['inputPassword']);

        $param = array();
        $param['mail'] = $_POST['inputMail'];
        $param['password'] = $password;

        //var_dump($param);
        $sql = 'SELECT uid FROM users WHERE mail=:mail AND password=:password AND active=1 AND verified=1';
        $userId = getDatabase()->one($sql, $param);


        if (empty($userId['uid'])) {
            getRoute()->redirect(getConfig()->get('global')->basepath . 'login/failed', null, true);
            return;
        }


        getSession()->set(Session::LOGGED_IN, 'true');
        getSession()->set(Session::USER_ID, $userId['uid']);

        getRoute()->redirect(getConfig()->get('global')->basepath . '', null, true);
        return;

        //getRoute()->redirect('/login');
        //getSession()->set('name', 'value');
    }

    /**
     * performing a logout and redirecting to the start page.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function logout()
    {

        getSession()->end();


        getRoute()->redirect(getConfig()->get('global')->basepath, null, true);
        return;
    }

}