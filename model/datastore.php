<?php


class DataStore
{


    static public function store()
    {

        // TODO check write permission

        $json = Api::getValidJson("./json-schema/store.json");
        if ($json == Null) Api::failure();

        $time = microtime(true);
        $streamId = $json->streamId;
        $value = $json->value;

        if ($json->checksum = !$streamId + $value)
            Api::failure();

        getDatabase()->execute('INSERT INTO store(streamId, val, loggedTime) VALUES(:streamId, :val, :loggedTime)', array(':streamId' => $streamId, ':loggedTime' => $time, ':val' => $value));

    }

    /*
     * get 5GB
     */
    static public function retrieve($streamId)
    {
        // TODO FIX IT !


        $exampleValues = array((string)microtime(true) => rand(0, 255));

        time_nanosleep(0, 5000000);
        $exampleValues = array_merge($exampleValues, array((string)microtime(true) => rand(0, 255)));

        time_nanosleep(0, 5000000);
        $exampleValues = array_merge($exampleValues, array((string)microtime(true) => rand(0, 255)));

        time_nanosleep(0, 5000000);
        $exampleValues = array_merge($exampleValues, array((string)microtime(true) => rand(0, 255)));

        time_nanosleep(0, 5000000);
        $exampleValues = array_merge($exampleValues, array((string)microtime(true) => rand(0, 255)));


        return $exampleValues;
    }

    /*
     * Retrieve Data which is newer than the from Timestamp
     */
    static public function retrieveFrom($streamId, $from)
    {
        // TODO FIX IT !

        // TODO ORDER BY loggedTime DESC

        $params = array();
        $params[":streamId"] = $streamId;
        $params[":loggedTime"] = $from;
        $result = getDatabase()->all('SELECT val, loggedTime FROM store WHERE streamId=:streamId AND loggedTime>:loggedTime', $params);

        $returnValue = array();

        foreach ($result as $value) {
            $returnValue[$value["loggedTime"]] = $value["val"];
        }
        return $returnValue;

    }

    /*
     * retrieve Data From newer than from and older to
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

    static public function subsample($data, $amountReturnValues)
    {
        //var_dump($data);

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
