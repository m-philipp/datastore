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
	 * authenticates if theres an valid token given as
	 * Auth-Token: token
	 * in the HTTP Header.
	 * @return userId (or exits with 403)
	 */
	static public function authenticate($right)
	{

		// TODO check if the correct Right is assured
		// TODO maybe some Users want that tha API can add Tasks but only on Board xy

		$header = Api::parseRequestHeaders();
		if (array_key_exists('Auth-Token', $header)) {
			$token = $header["Auth-Token"];
			$result = getDatabase()->one('SELECT userId, validUntil FROM tokens WHERE hash=:hash', array(':hash' => $token));


			if (!empty($result['validUntil'])) {
				$validUntil = new DateTime($result['validUntil']);
				$now = new DateTime("now");

				if ($validUntil < $now) {
					header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
					exit;
				}
			} else {
				header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
				exit;
			}


			if (array_key_exists('userId', $result) && !empty($result['userId'])) {

				$status = getDatabase()->one('SELECT status FROM user WHERE userId=:userId', array(':userId' => $result['userId']));

				if (array_key_exists('status', $status) && is_numeric($status['status']) && $status['status'] > 0) {
					return $result['userId'];
				}
			}
		}

		header($_SERVER["SERVER_PROTOCOL"] . " 403 Forbidden");
		exit;
	}

	/**
	 * should use /dev/urandom in productive environment
	 */
	static public function randomString($chars = 8)
	{
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
