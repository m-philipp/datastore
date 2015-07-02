<?php

//Class for Pre Alpha Navigation functionality

class Data
{


    public static function viewStream($streamId)
    {
        Util::checkLogin();

        $params = Util::initNavigation('./data/viewStream');
        $params['content'] = getTemplate()->get('viewStream.php', $params);
        getTemplate()->display('layout.php', $params);

    }

    public static function view($streamId)
    {
        $params = array();
        $params['streamId'] = $streamId;
        $result = getDatabase()->all('SELECT loggedTime FROM store WHERE streamId=:streamId ORDER BY loggedTime DESC LIMIT 0, 30 ', $params);

        if (count($result) < 2) {
            exit;
        } // TODO

        $startTimestamp = $result[0]['loggedTime'] - 3600;
        $endTimestamp = $result[count($result) - 1]['loggedTime'] + 3600;

        // 2000/01/01 00:00
        $end = date('Y/m/d H:i', $endTimestamp);
        $start = date('Y/m/d H:i', $startTimestamp);

        $params = array();
        $params['navigation'] = self::$navigation;
        $params['content'] = getTemplate()->get('view.php', array('stream' => $streamId, 'start' => $start, 'end' => $end, 'startTimestamp' => $startTimestamp, 'endTimestamp' => $endTimestamp));
        $params['bp'] = './../';

        getTemplate()->display('layout.php', $params);
    }

    public static function viewList()
    {
        Util::checkLogin();

        $params = Util::initNavigation('./data/viewStream');

        $result = getDatabase()->all('SELECT DISTINCT sid FROM store ORDER BY sid ASC');

        $streams = array();
        foreach ($result as $val) {
            $streams[] = $val['sid'];
        }

        $params['content'] = getTemplate()->get('viewList.php', array('streams' => $streams));

        getTemplate()->display('layout.php', $params);

    }
}
