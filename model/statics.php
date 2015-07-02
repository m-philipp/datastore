<?php

class Statics
{

    public static function test()
    {

        $params = Util::initNavigation('./impressum');
        $params['content'] = getTemplate()->get('impressum.php', $params);


        getTemplate()->display('layout.php', $params);
    }

    public static function error()
    {
        $params = Util::initNavigation('./');
        $params['content'] = getTemplate()->get('error.php', $params);

        getTemplate()->display('layout.php', $params);
    }

    public static function start()
    {
        $params = Util::initNavigation('./');
        $params['content'] = getTemplate()->get('start.php', $params);

        getTemplate()->display('layout.php', $params);
    }

    public static function apiDocumentation()
    {
        $params = Util::initNavigation('./apiDocumentation');
        $params['content'] = getTemplate()->get('apiDocumentation.php', $params);

        getTemplate()->display('layout.php', $params);
    }

    public static function impressum()
    {
        $params = Util::initNavigation('./impressum');
        $params['content'] = getTemplate()->get('impressum.php', $params);

        getTemplate()->display('layout.php', $params);
    }

    public static function help()
    {
        $params = Util::initNavigation('./help');
        $params['content'] = getTemplate()->get('help.php', $params);


        getTemplate()->display('layout.php', $params);
    }

    public static function faq()
    {
        $params = Util::initNavigation('./faq');
        $params['content'] = getTemplate()->get('faq.php', $params);


        getTemplate()->display('layout.php', $params);
    }
}
