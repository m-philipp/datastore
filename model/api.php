<?php

include("./model/datastore.php");


/**
 * Class Api
 *
 * Containing most of the supportive functionality for the API.
 */
class Api
{

    /**
     * API to return the current Version.
     * @return string $version the Version of this API
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function getVersion()
    {
        //header($_SERVER["SERVER_PROTOCOL"]." 418 I’m a teapot");
        return array("version" => "0.1.0");
    }

    /**
     * Preprocessing to implement the CORS Headers.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function preprocessing()
    {
        // TODO do some DoS countermeasures, and prevent token Brute-Forcing

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


    /**
     * Shorthand for exiting with a 201 Created Statuscode.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function created()
    {
        header($_SERVER["SERVER_PROTOCOL"] . " 201 Created");
        exit;
    }

    /**
     * Shorthand for exiting with a 400 Bad Request Statuscode.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function failure()
    {
        header($_SERVER["SERVER_PROTOCOL"] . " 400 Bad Request");
        exit;
    }

    /**
     * If the client is authenticated the return value will contain the uid.
     * If the request is not authenticated with needed read/write permissions it exits with a 403.
     * For the Authentication a valid Header Auth-Token or a valid Session Cookie is needed.
     * @params bool read if read rights on the streams are needed
     * @params bool write if write rights on the streams are needed
     * @return int userId (or exits with 403)
     * @author Martin Philipp <mail@martin-philipp.de>
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

    /**
     * Checks the if a valid Session exists.
     * @return int userId (or -1)
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function authenticateViaSession()
    {

        if (is_numeric(getSession()->get(Session::USER_ID))) {
            return getSession()->get(Session::USER_ID);
        }
        return -1;
    }

    /**
     * Checks the if a valid Token with the needed rights exists.
     * @param bool $read right
     * @param bool $write right
     * @return int userId (or -1)
     * @author Martin Philipp <mail@martin-philipp.de>
     */
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
     * generate random lowercase characters (of given length)
     * @param int $chars length of the random array
     * @return string randomCharacters
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function randomString($chars = 8)
    {
        // TODO check randomness !
        // maybe use /dev/urandom in productive environment

        $letters = 'abcefghijklmnopqrstuvwxyz';
        return substr(str_shuffle($letters), 0, $chars);
    }

    /**
     * validates the JSON given in HTTP-Body against scheme file
     * @param string $jsonSchemeFile path to the JSON Scheme File
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

    /**
     * Parse the HTTP Request Headers as Array.
     * @return array $headers containing the HTTP Request Headers
     */
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
