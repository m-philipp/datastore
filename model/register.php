<?php

/**
 * Created by PhpStorm.
 * User: martin
 * Date: 29.06.2015
 * Time: 05:32
 */
Class Register
{

    /**
     * Model generating the Register Page with an optional value for a fail message.
     * @param bool $error
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function generateRegisterPage($error = false)
    {
        $params = Util::initNavigation('./register');
        $params['error'] = $error;
        $params['content'] = getTemplate()->get('register.php', $params);
        getTemplate()->display('layout.php', $params);
    }

    /**
     *
     */
    public static function getRegisterPageWithFail()
    {
        Self::generateRegisterPage(true);
    }

    /**
     * Model creating the new User in the DB and redirecting to fail / success pages.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function sendIn()
    {

        if (isset($_POST['mail']) && $_POST['mail'] != "" &&
            !empty($_POST['inputPassword']) &&
            $_POST['inputPassword'] == $_POST['inputPassword2'] &&
            strlen($_POST['inputPassword']) > 5
        ) {

            $sql = 'SELECT uid FROM users WHERE mail=:mail';
            $mail = getDatabase()->one($sql, array(':mail' => $_POST['mail']));

            if (!empty($mail['uid'])) {
                getRoute()->redirect(getConfig()->get('global')->basepath . 'register/fail', null, true);
                return;
            }

            $password = hash("sha512", getConfig()->get('global')->salt . $_POST['inputPassword']);

            $verifyCode = Api::randomString();

            getDatabase()->execute('INSERT INTO users(mail, password, verified) VALUES(:mail, :password, :verified)',
                array(
                    ":mail" => $_POST['mail'],
                    ':password' => $password,
                    ":verified" => $verifyCode
                ));

            self::registerMail($_POST['mail'], $verifyCode);

        } else {
            getRoute()->redirect(getConfig()->get('global')->basepath . 'register/fail', null, true);
            return;
        }


        getRoute()->redirect(getConfig()->get('global')->basepath . 'register/success', null, true);
    }

    /**
     * Model generating the Register success page.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function registerSuccess()
    {
        $params = Util::initNavigation('./');
        $params['content'] = getTemplate()->get('registerSuccess.php');

        getTemplate()->display('layout.php', $params);
    }

    /**
     * verifying the verify code and viewing a success page.
     * @param string $code the verify code the user got by mail.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    public static function verify($code)
    {

        $sql = 'SELECT uid FROM users WHERE verified=:verified';
        $userId = getDatabase()->one($sql, array(":verified" => $code));
        if (empty($userId['uid'])) {
            getRoute()->redirect(getConfig()->get('global')->basepath . 'error', null, true);
            return;
        }

        getDatabase()->execute('UPDATE users SET verified=:verified WHERE uid=:uid',
            array(
                ':verified' => 1,
                ':uid' => $userId['uid']
            ));

        $params = Util::initNavigation('./');
        $params['content'] = getTemplate()->get('registrationVerified.php');
        getTemplate()->display('layout.php', $params);
    }


    /**
     * Send a register Mail to the user with a verify code to ensure the mail Address is correct.
     * @param string $mailRegistree user mail address.
     * @param string $verifyCode the verfy code to be mailed.
     * @return bool
     * @throws Exception
     * @throws phpmailerException
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function registerMail($mailRegistree, $verifyCode)
    {

        // TODO


        $mail = new PHPMailer;
        /*
                $mail->isSMTP();                            // Set mailer to use SMTP
                $mail->Host = 'smtp.sysproserver.de';       // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                     // Enable SMTP authentication
                $mail->Username = 'mailbot@lean2do.de';     // SMTP username
                $mail->Password = 'rctQvXZUMbmFbqrJKrmzd';  // SMTP password
                // $mail->SMTPSecure = 'tls';               // Enable encryption, 'ssl' also accepted
        */

        $mail->From = 'mailbot@arcwind.de';
        $mail->FromName = 'Registrierung auf Arcwind';
        $mail->addAddress($mailRegistree);     // Add a recipient
        //$mail->addAddress('martin.zittel@gmail.com','Martin Zittel');     // Add a recipient
        $mail->addReplyTo('noreply@arcwind.de', 'No Reply');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        $mail->WordWrap = 70;                                 // Set word wrap to 50 characters
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Ihre Registrierung auf Arcwind';
        $mail->Body = 'Hallo,<br />Ihre Registrierung auf Arcwind war erfolgreich.<br />Ihr Benutzername lautet: ' . $mailRegistree . '<br />Um Ihre E-Mail Adresse zu verifizieren, klicken Sie bitte auf den folgenden Link.<br /><br /><a href=\'http://www.arcwind.de/register/verify/' . $verifyCode . '\'>http://www.arcwind.de/register/' . $verifyCode . '</a><br /><br />Mit freundlichen Grüßen<br />Ihr Arcwind Team';
        $mail->AltBody = 'Hallo,
Ihre Registrierung auf Arcwind war erfolgreich.
Ihr Benutzername lautet: ' . $mailRegistree . '
Um Ihre E-Mail Adresse zu verifizieren, klicken Sie bitte auf den folgenden Link.

http://www.arcwind.de/register/verify/' . $verifyCode . '

Mit freundlichen Grüßen
Ihr Arcwind Team';


        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }

    }

}