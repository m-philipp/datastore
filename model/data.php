<?php

//Class for Pre Alpha Navigation functionality

class Data
{


    public static function viewStream($streamId)
    {
        Util::checkLogin();
        $params = Util::initNavigation('./data/view/' . $streamId);

        $result = getDatabase()->one('SELECT comment FROM user2store WHERE sid=:sid AND uid=:uid',
            array(
                ":sid" => $streamId,
                ":uid" => getSession()->get(Session::USER_ID)));

        if (empty($result)) {
            getRoute()->redirect(getConfig()->get('global')->basepath . 'error', null, true);
            return;
        }

        /* TODO:
         * view the last 1k values and make the granularity selectable.
         */

        $params['comment'] = $result['comment'];
        $params['sid'] = $streamId;
        $params['content'] = getTemplate()->get('viewStream.php', $params);

        getTemplate()->display('layout.php', $params);

    }

    public static function view($streamId)
    {
        Util::checkLogin();
        $params = Util::initNavigation('./data/view/' . $streamId);

        $result = getDatabase()->one('SELECT comment FROM user2store WHERE sid=:sid AND uid=:uid',
            array(
                ":sid" => $streamId,
                ":uid" => getSession()->get(Session::USER_ID)));

        if (empty($result)) {
            getRoute()->redirect(getConfig()->get('global')->basepath . 'error', null, true);
            return;
        }

        $params['comment'] = $result['comment'];
        $params['sid'] = $streamId;
        $params['content'] = getTemplate()->get('view.php', $params);

        getTemplate()->display('layout.php', $params);
    }

    public static function viewList()
    {
        Util::checkLogin();

        $params = Util::initNavigation('./data/viewStream');

        $results = getDatabase()->all('SELECT DISTINCT sid, comment FROM user2store WHERE uid=:uid ORDER BY sid ASC',
            array(":uid" => getSession()->get(Session::USER_ID)));


        $params['streams'] = $results;

        for ($i = 0; $i < count($params['streams']); $i++) {
            $count = getDatabase()->one('SELECT COUNT(id) FROM store WHERE sid=:sid',
                array(":sid" => $params['streams'][$i]['sid']));
            $params['streams'][$i]["count"] = $count["COUNT(id)"];
        }


        $params['content'] = getTemplate()->get('viewList.php', $params);

        getTemplate()->display('layout.php', $params);

    }


    public static function addStream()
    {
        Util::checkLogin();

        if (empty($_POST['comment'])) {
            getRoute()->redirect(getConfig()->get('global')->basepath . 'data', null, true);
            return;
        }


        $sid = getDatabase()->one('SELECT sid FROM user2store ORDER BY sid DESC LIMIT 1')['sid'] + 1;

        getDatabase()->execute('INSERT INTO user2store(uid, sid, comment) VALUES(:uid, :sid, :comment)',
            array(
                ":uid" => getSession()->get(Session::USER_ID),
                ':sid' => $sid,
                ":comment" => $_POST['comment']
            ));

        getRoute()->redirect(getConfig()->get('global')->basepath . 'data', null, true);
        return;
    }

    public static function deleteStream($stream)
    {
        Util::checkLogin();

        $sql = 'SELECT id FROM user2store WHERE uid=:uid AND sid=:sid';
        $id = getDatabase()->one($sql,
            array(
                ':uid' => getSession()->get(Session::USER_ID),
                ':sid' => $stream
            ));

        if (empty($id)) {
            getRoute()->redirect(getConfig()->get('global')->basepath . 'error', null, true);
            return;
        }

        $affectedRows = getDatabase()->execute('DELETE FROM user2store WHERE id=:id',
            array(
                ':id' => $id['id']
            ));

        if (empty($affectedRows)) {
            getRoute()->redirect(getConfig()->get('global')->basepath . 'error', null, true);
            return;
        }


        getRoute()->redirect(getConfig()->get('global')->basepath . 'data', null, true);
        return;

    }
}
