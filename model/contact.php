<?php

/**
 * Created by PhpStorm.
 * User: martin
 * Date: 29.06.2015
 * Time: 03:36
 */
class Contact
{

    public static function storeContactRequest()
    {
        $form = empty($_POST['form']) ? "default" : $_POST['form'];

        if (!empty($_POST['mail']) && !empty($_POST['content'])
        ) {

            $ret = getDatabase()->execute('INSERT INTO contacts(mail, content, form) VALUES(:mail, :content, :form)',
                array(
                    ":mail" => $_POST['mail'],
                    ':content' => $_POST['content'],
                    ":form" => $form
                ));
            if (!empty($ret)) {
                getRoute()->redirect(getConfig()->get('global')->basepath . 'contact/success', null, true);
                return;
            }
        }

        getRoute()->redirect(getConfig()->get('global')->basepath . 'contact/fail', null, true);

    }

    public static function generateContactForm($fail = false)
    {
        $params = Util::initNavigation('./contact');
        $params['fail'] = $fail;
        $params['content'] = getTemplate()->get('contact.php', $params);
        getTemplate()->display('layout.php', $params);
    }


    public static function contactFail()
    {
        self::generateContactForm(true);
    }


    public static function contactSuccess()
    {
        $params = Util::initNavigation('./contact/success');
        $params['content'] = getTemplate()->get('contactSuccess.php', $params);
        getTemplate()->display('layout.php', $params);
    }


}