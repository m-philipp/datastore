<?php

include("./model/datastore.php");


class Api
{

    /**
     * @author A. Radsziwill radsziwill@stud.tu-darmstadt.de
     * @return string the Version of this API
     */
    static public function getVersion()
    {
        //header($_SERVER["SERVER_PROTOCOL"]." 418 I’m a teapot");
        return array("version" => "0.1");
    }

    /**
     * @author Martin Zittel <martin.zittel@gmail.com>
     */
    static public function preprocessing()
    {
        // TODO do some DoS countermeasures, and prevent token Brute-Forcing

        // (store hashed IP).
        // esp. for:
        // * addUser
        // * login (and all the rest API Calls that require to own a valid token)

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: x-requested-with, Content-Type");

        if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
            exit;
        }

        $header = API::parseRequestHeaders();

        if (!empty($header['x-requested-with']))
            header("x-requested-with: " . $header['x-requested-with']);


    }


    static public function test($foo, $bar)
    {
        return array($foo, $bar);
    }


    static public function created()
    {
        header($_SERVER["SERVER_PROTOCOL"] . " 201 Created");
        exit;
    }

    static public function failure()
    {
        header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
        exit;
    }

    /**
     * If the client is authenticated the return value will contain the uid.
     * If not authenticated with given read/write permissions it exits with 403.
     * Auth-Token: token
     * in the HTTP Header.
     * @params read if read rights on the streams are needed
     * @params write if write rights on the streams are needed
     * @return userId (or exits with 403)
     */
    static public function authenticate($read = true, $write = true)
    {

        // TODO check if the correct Right is assured

        $viaHeader = self::authenticateViaHeaderToken($read, $write);
        $viaSession = self::authenticateViaSession();

        if ($viaHeader > -1) {
            return $viaHeader;
        } elseif ($viaSession > -1) {
            return $viaSession;
        }

        header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
        exit;

    }

    static public function authenticateViaSession()
    {

        if (is_numeric(getSession()->get(Session::USER_ID))) {
            return getSession()->get(Session::USER_ID);
        }
        return -1;
    }

    static public function authenticateViaHeaderToken($read, $write)
    {

        $header = Api::parseRequestHeaders();


        if (!array_key_exists('Auth-Token', $header)) {
            return -1;
        }

        $token = $header["Auth-Token"];
        $result = getDatabase()->one('SELECT uid, validTo FROM tokens WHERE token=:token
                    AND r>' . ($read ? '0' : '-1') . '
                    AND w>' . ($write ? '0' : '-1'),
            array(':token' => $token));


        if (empty($result['validTo'])) {
            return -1;
        }

        //$validUntil = new DateTime($result['validTo']);
        //$now = new DateTime("now");

        if (time() > $result['validTo']) {
            return -1;
        }

        if (!array_key_exists('uid', $result) or empty($result['uid'])) {
            return -1;
        }

        $active = getDatabase()->one('SELECT uid, active FROM users WHERE uid=:uid AND verified=1', array(':uid' => $result['uid']));

        if (array_key_exists('active', $active) && is_numeric($active['active']) && $active['active'] > 0) {
            return $active['uid'];
        } else {
            return -1;
        }


    }

    /**
     * should use /dev/urandom in productive environment
     */
    static public function randomString($chars = 8)
    {
        // TODO check randomness !
        $letters = 'abcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($letters), 0, $chars);
    }

    /**
     * should use /dev/urandom in productive environment
     */
    static public function generateToken($userId, $leaseLength = 1800)
    {

        $token = hash("sha512", Api::randomString(20));

        $date = new DateTime("now");
        $date->add(new DateInterval('PT30M'));

        $params = array();
        $params['userId'] = $userId;
        $params['hash'] = $token;
        $params['validUntil'] = $date->format('Y-m-d H:i:s');
        $params['rights'] = "1";

        $userId = getDatabase()->execute('INSERT INTO tokens(userId, hash, validUntil, rights)
			VALUES(:userId, :hash, :validUntil, :rights)', $params);

        return $token;
    }

    /**
     * validates the JSON given in HTTP-Body against scheme file
     * @return JSON-Object or NULL
     */
    static public function getValidJson($jsonSchemeFile)
    {
        $data = json_decode(file_get_contents("php://input"));

        $schema = json_decode(file_get_contents($jsonSchemeFile));

        if (!$schema or !$data) {
            return Null;
        }

        $result = JsonSchema::validate($data, $schema);


        if ($result->valid) {
            return $data;
        } else {
            return Null;
        }

    }

    static public function parseRequestHeaders()
    {
        $headers = array();
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }
        return $headers;
    }
}

?>
