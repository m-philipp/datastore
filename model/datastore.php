<?php


/**
 * Class DataStore
 *
 * The DataStore contains the API for the Data related functionality.
 */
class DataStore
{


    /**
     * API for storing a list of Timestamp, Value Pairs int the DB.
     * @param int $sid the respective Stream where the data is supposed to be stored.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function storeList($sid)
    {
        $uid = Api::authenticate(false, true);


        if (!is_numeric($sid))
            API::failure();

        $json = Api::getValidJson("./json-schema/store-list.json");
        if ($json == Null) Api::failure();


        $result = getDatabase()->all('SELECT id FROM user2store WHERE sid=:streamId AND uid=:uid', array(":streamId" => $sid, ":uid" => $uid));

        if (empty($result)) {
            Api::failure();
        }

        foreach ($json as $datum) {
            $time = $datum->time;
            $value = $datum->value;
            $checksum = $datum->checksum;

            if (!self::calculateChecksum($checksum, $uid, $sid, $time, $value))
                Api::failure();

            getDatabase()->execute('INSERT INTO store(sid, val, loggedTime) VALUES(:streamId, :val, :loggedTime)', array(':streamId' => $sid, ':loggedTime' => $time, ':val' => $value));
        }
    }

    /**
     * API Storgage Endpoint for the storage of a single timestamp value pair.
     * @param int $sid the respective Stream where the data is supposed to be stored.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function store($sid)
    {

        $uid = Api::authenticate(false, true);


        if (!is_numeric($sid))
            API::failure();

        $json = Api::getValidJson("./json-schema/store.json");
        if ($json == Null) Api::failure();


        $time = $json->time;
        $value = $json->value;

        $result = getDatabase()->all('SELECT id FROM user2store WHERE sid=:streamId AND uid=:uid', array(":streamId" => $sid, ":uid" => $uid));

        if (empty($result)) {
            Api::failure();
        }

        if (!self::calculateChecksum($json->checksum, $uid, $sid, $time, $value))
            Api::failure();

        getDatabase()->execute('INSERT INTO store(sid, val, loggedTime) VALUES(:streamId, :val, :loggedTime)', array(':streamId' => $sid, ':loggedTime' => $time, ':val' => $value));

    }

    /**
     * API Endpoint for the storage of a value only. Timestamp is set to now.
     * @param int $sid the StreamID for the saved data.
     * @param float $val value to save.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function storeVal($sid, $val)
    {

        $uid = Api::authenticate(false, true);


        if (!is_numeric($sid) && !is_numeric($val))
            API::failure();

        $result = getDatabase()->all('SELECT id FROM user2store WHERE sid=:streamId AND uid=:uid', array(":streamId" => $sid, ":uid" => $uid));

        if (empty($result)) {
            Api::failure();
        }

        $time = round(microtime(true) * 1000);


        getDatabase()->execute('INSERT INTO store(sid, val, loggedTime) VALUES(:streamId, :val, :loggedTime)', array(':streamId' => $sid, ':loggedTime' => $time, ':val' => $val));

    }

    /**
     * API Endpoint to retrieve a specified amount of Data.
     * @param int $streamId the StreamID from the requested data.
     * @param int $numValues the amount of data requested.
     * @return array $values the requested data array
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function retrieveNumValues($streamId, $numValues)
    {
        $uid = Api::authenticate(true, false);


        $result = getDatabase()->all('SELECT id FROM user2store WHERE sid=:streamId AND uid=:uid', array(":streamId" => $streamId, ":uid" => $uid));

        if (empty($result)) {
            Api::failure();
        }

        if (!is_numeric($numValues))
            API::failure();


        $params = array();
        $params[":streamId"] = $streamId;

        $result = getDatabase()->all('SELECT val, loggedTime FROM store WHERE sid=:streamId ORDER BY loggedTime DESC LIMIT ' . $numValues, $params);


        $returnValue = array();
        for ($i = 0;
             $i < count($result);
             $i++) {
            $returnValue[] = array(
                (float)$result[$i]['loggedTime'],
                (float)$result[$i]['val']);
        }

        return array("data" => self::subsample($returnValue, (int)getConfig()->get('global')->maxSubsamples));

    }


    /**
     * API Endpoint to retrieve since a given point of time.
     * @param int $streamId the StreamID from the requested data.
     * @param int $from starting timepoint.
     * @return array $values the requested data array
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function retrieveFrom($streamId, $from)
    {
        $to = (int)(microtime(true) * 1000);
        return self::retrieveFromTo($streamId, $from, $to);
    }

    /*
     * retrieve Data From newer than from and older to
     */
    /**
     * API Endpoint to retrieve a given timespan.
     * @param int $streamId the StreamID from the requested data.
     * @param int $from starting timepoint.
     * @param int $to end timepoint.
     * @return array the requested data array.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function retrieveFromTo($streamId, $from, $to)
    {
        $uid = Api::authenticate(true, false);


        $result = getDatabase()->all('SELECT id FROM user2store WHERE sid=:streamId AND uid=:uid', array(":streamId" => $streamId, ":uid" => $uid));

        if (empty($result)) {
            Api::failure();
        }

        if (!is_numeric($from) OR !is_numeric($to))
            API::failure();


        $params = array();
        $params[":streamId"] = $streamId;
        $params[":from"] = $from;
        $params[":to"] = $to;

        $result = getDatabase()->all('SELECT val, loggedTime FROM store WHERE sid=:streamId AND loggedTime>:from AND loggedTime<:to', $params);


        $returnValue = array();
        for ($i = 0;
             $i < count($result);
             $i++) {
            $returnValue[] = array(
                (float)$result[$i]['loggedTime'],
                (float)$result[$i]['val']);
        }

        return array("data" => self::subsample($returnValue, (int)getConfig()->get('global')->maxSubsamples));


    }

    /**
     * Checksum checker.
     * @param float $checksum the proposed checksum
     * @param int $uid userID
     * @param int $streamId StreamID
     * @param float $time timestamp
     * @param float $value value
     * @return bool $valid if the checksum is correct
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function calculateChecksum($checksum, $uid, $streamId, $time, $value)
    {
        if (!is_numeric($checksum)
            or !is_numeric($uid)
            or !is_numeric($streamId)
            or !is_numeric($time)
            or !is_numeric($value)
        ) {
            return false;
        } elseif ($time < 1400000000000) {
            return false;
        } elseif ($checksum != $time + $value) {
            return false;
        }

        return true;
    }

    /**
     * linear Subsampling to reduce an array retrieved Data to an given length.
     * @param array $data the timestamp value pairs of the full data array.
     * @param int $amountReturnValues amount of the subsamples to be returned.
     * @return mixed $subsampledData the subsampled data.
     * @author Martin Philipp <mail@martin-philipp.de>
     */
    static public function subsample($data, $amountReturnValues)
    {
        // TODO fasten it up!

        if ($amountReturnValues >= count($data) or count($data) < 3)
            return $data;

        $distanceVector = array();
        $minDistance = PHP_INT_MAX;
        $minDistanceIndex = 0;

        for ($i = 1; $i < count($data) - 1; $i++) {
            $p1x = (float)$data[$i - 1][0];
            $p1y = (float)$data[$i - 1][1];
            $p2x = (float)$data[$i + 1][0];
            $p2y = (float)$data[$i + 1][1];

            $candidateX = (float)$data[$i][0];
            $candidateY = (float)$data[$i][1];

            // y = m * x + b

            $m = ($p2y - $p1y) / ($p2x - $p1x);
            $b = $p1y - $m * $p1x;

            $newY = $m * $candidateX + $b;

            $distanceVector[] = abs($candidateY - $newY);
        }

        for ($i = 0; $i < count($distanceVector); $i++) {
            if ($minDistance > $distanceVector[$i]) {
                $minDistance = $distanceVector[$i];
                $minDistanceIndex = $i + 1;
            }
        }

        array_splice($data, $minDistanceIndex, 1);

        return self::subsample($data, $amountReturnValues);
    }

}

?>
