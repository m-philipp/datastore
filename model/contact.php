<?php

/**
 * Created by PhpStorm.
 * User: martin
 * Date: 29.06.2015
 * Time: 03:36
 */
class Contact
{

    /**
     * Stores a POSTed contact request. Viewing success page or redirecting to error page.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
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

    /**
     * Model generating a contact form with an optional fail message.
     * @param bool $fail if the prev. send in failed.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function generateContactForm($fail = false)
    {
        $params = Util::initNavigation('./contact');
        $params['fail'] = $fail;
        $params['content'] = getTemplate()->get('contact.php', $params);
        getTemplate()->display('layout.php', $params);
    }


    /**
     * Model viewing the Contact page with a failure message.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function contactFail()
    {
        self::generateContactForm(true);
    }


    /**
     * Model viewing the success message after sending in a contact form.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function contactSuccess()
    {
        $params = Util::initNavigation('./contact/success');
        $params['content'] = getTemplate()->get('contactSuccess.php', $params);
        getTemplate()->display('layout.php', $params);
    }


}