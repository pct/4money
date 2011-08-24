<?php

require 'Slim/Slim.php';
require 'Slim/Views/HaangaView.php';
require 'lib/idiorm.php';
require 'config.php';

define('PROJECT_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('WKHTMLTOPDF_BIN_PATH', $WKHTMLTOPDF_BIN_PATH);

ORM::configure("mysql:dbname=$DB_NAME;host=$DB_HOST");
ORM::configure('username', $DB_USER);
ORM::configure('password', $DB_PASS);

ORM::configure('id_column_overrides', array(
    'customer'  => 'customer_id',
    'quotation' => 'quotation_id',
    'option'    => 'option_id',
));

$slim_settings = array(
    'debug'              => true, # @todo set to false if on production!
    'log.enable'         => true,
    'view'               => new HaangaView('./lib/Haanga', './tpl', './tpl_cache'),
    'templates.path'     => './tpl',
    'cookies.secret_key' => $COOKIES_SECRET_KEY,
);

$app = new Slim($slim_settings);
require 'app.php';
$app->run();

?>
