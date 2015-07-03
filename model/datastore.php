<?php


class DataStore
{


    static public function store()
    {
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


        if (!is_numeric($from) OR !is_numeric($to))
            API::failure();


        $params = array();
        $params[":streamId"] = $streamId;
        $params[":from"] = $from;
        $params[":to"] = $to;

        $result = getDatabase()->all('SELECT val, loggedTime FROM store WHERE sid=:streamId AND loggedTime>:from AND loggedTime<:to', $params);

        $returnValue = array();

        foreach ($result as $value) {
            $returnValue[$value["loggedTime"]] = $value["val"];
        }
        return $returnValue;

    }

}

?>
