<?php

/**
 * Created by PhpStorm.
 * User: martin
 * Date: 29.06.2015
 * Time: 05:32
 */
Class Register
{

    public static function generateRegisterPage($error = false)
    {
        $params = Util::initNavigation('./register');
        $params['error'] = $error;
        $params['content'] = getTemplate()->get('register.php', $params);
        getTemplate()->display('layout.php', $params);
    }

    public static function getRegisterPageWithFail()
    {
        Self::generateRegisterPage(true);
    }

    public static function sendIn()
    {

        if (isset($_POST['mail']) && $_POST['mail'] != "" &&
            !empty($_POST['inputPassword']) &&
            $_POST['inputPassword'] == $_POST['inputPassword2'] &&
            strlen($_POST['inputPassword']) > 5
        ) {

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

    public static function registerSuccess()
    {
        $params = Util::initNavigation('./');
        $params['content'] = getTemplate()->get('registerSuccess.php');

        getTemplate()->display('layout.php', $params);
    }

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
        $mail->Body = 'Hallo,<br />Ihre Registrierung auf Arcwind war erfolgreich.<br />Ihr Benutzername lautet: ' . $mailRegistree . '<br />Um Ihre E-Mail Adresse zu verifizieren, klicken Sie bitte auf den folgenden Link.<br /><br /><a href=\'http://www.arcwind.de/register/' . $verifyCode . '\'>http://www.arcwind.de/register/' . $verifyCode . '</a><br /><br />Mit freundlichen Grüßen<br />Ihr Arcwind Team';
        $mail->AltBody = 'Hallo,
Ihre Registrierung auf Arcwind war erfolgreich.
Ihr Benutzername lautet: ' . $mailRegistree . '
Um Ihre E-Mail Adresse zu verifizieren, klicken Sie bitte auf den folgenden Link.

http://www.arcwind.de/register/' . $verifyCode . '

Mit freundlichen Grüßen
Ihr Arcwind Team';


        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }

    }

}