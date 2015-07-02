<?php

date_default_timezone_set('UTC');

require_once './lib/JsonSchema.php';
require_once './lib/JsonSchemaUndefined.php';

require './lib/phpmailer/PHPMailerAutoload.php';

// Load Epiphany
include_once './lib/epiphany/Epi.php';

// set config path depending on location of config.ini (= basic test if deployed or not)
/*
 * Password Hashing is done via:
 * hash("sha512", getConfig()->get('mysql')->salt . $pw);
 **/
$config = './';


EPi::setPath('config', $config);
Epi::setPath('base', './lib/epiphany');
Epi::setPath('documentRoot', './');
Epi::setPath('view', './view');

// Initialize all Epi modules that are used
Epi::init('route', 'template', 'api', 'database', 'config', 'session');
getConfig()->load('config.ini');

// Connect to database
$sql_conf = getConfig()->get('mysql');

EpiDatabase::employ('mysql', $sql_conf->database,
    $sql_conf->server,
    $sql_conf->user,
    $sql_conf->password);
EpiSession::employ(EpiSession::PHP);

include './constants.session.php';
include './util.php';

//Load class that implements routing functionality
include './model/data.php';
include './model/statics.php';
include './model/login.php';
include './model/settings.php';
include './model/register.php';
include './model/contact.php';
include './model/tokens.php';

//Load class that implements API functionality
include './model/api.php';


//Set config Path to same folder as index.php because getRoute()->load uses the config path to look for the routes.ini
//Epi::setPath('config', './');


//Setup routes for navigation and API calls
getRoute()->set_preprocessing('preprocessing', 'API');
getRoute()->loadDir('./routes/uiRoutes');
getAPI()->loadDir('./routes/apiRoutes');
getRoute()->run();
