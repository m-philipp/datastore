<?php

/**
 * Created by PhpStorm.
 * User: martin
 * Date: 29.06.2015
 * Time: 05:32
 */
Class Settings
{

    /**
     * Model generating the settings Page. Error / Success Messages can be shown via the parameters.
     * @param bool $success if the settings change was successful.
     * @param bool $error if the settings change failed.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function generateSettingsPage($success = false, $error = false)
    {
        Util::checkLogin();

        $sql = 'SELECT mail FROM users WHERE uid=:uid';
        $mail = getDatabase()->one($sql, array(':uid' => getSession()->get(Session::USER_ID)));

        if (empty($mail['mail'])) {
            getRoute()->redirect(getConfig()->get('global')->basepath . 'error', null, true);
            return;
        }

        $params = Util::initNavigation('./settings');
        $params['mail'] = $mail['mail'];
        $params['success'] = $success;
        $params['error'] = $error;
        $params['content'] = getTemplate()->get('settings.php', $params);
        getTemplate()->display('layout.php', $params);
    }

    /**
     * Model generating the settings Page with a Success Message.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function getSettingsPageWithSuccess()
    {
        Util::checkLogin();
        Self::generateSettingsPage(true);
    }

    /**
     * Model generating the settings Page with a Error Message due to failed settings changes.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function getSettingsPageWithError()
    {
        Util::checkLogin();
        Self::generateSettingsPage(false, true);
    }

    /**
     * Model updating the settings page and redirecting to the success / error settings pages.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function update()
    {
        Util::checkLogin();

        if (isset($_POST['mail']) && $_POST['mail'] != "") {
            $affectedRows = getDatabase()->execute('UPDATE users SET mail=:mail WHERE uid=:uid',
                array(
                    ':mail' => $_POST['mail'],
                    ':uid' => getSession()->get(Session::USER_ID)
                ));
        }

        if (!empty($_POST['inputPassword']) or !empty($_POST['inputPassword2'])) {
            echo "something submitted";
            if ($_POST['inputPassword'] == $_POST['inputPassword2']
                && strlen($_POST['inputPassword']) > 5
            ) {
                $password = hash("sha512", getConfig()->get('global')->salt . $_POST['inputPassword']);
                getDatabase()->execute('UPDATE users SET password=:password WHERE uid=:uid',
                    array(
                        ':password' => $password,
                        ':uid' => getSession()->get(Session::USER_ID)
                    ));
            } else {
                getRoute()->redirect(getConfig()->get('global')->basepath . 'settings/error', null, true);
                return;
            }
        }


        getRoute()->redirect(getConfig()->get('global')->basepath . 'settings/success', null, true);
    }


}