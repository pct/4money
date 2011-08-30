<?php

require 'Slim/Slim.php';
require 'Slim/Views/HaangaView.php';
require 'lib/idiorm.php';
require 'config.php';

$pdf_enable = (file_exists($WKHTMLTOPDF_BIN_PATH)) ? TRUE : FALSE;

define('PROJECT_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('WKHTMLTOPDF_BIN_PATH', $WKHTMLTOPDF_BIN_PATH);
define('PDF_ENABLE', $pdf_enable);

ORM::configure("mysql:dbname=$DB_NAME;host=$DB_HOST");
ORM::configure('username', $DB_USER);
ORM::configure('password', $DB_PASS);
ORM::configure('driver_options', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

ORM::configure('id_column_overrides', array(
    'customer'  => 'customer_id',
    'quotation' => 'quotation_id',
    'option'    => 'option_id',
));

$slim_settings = array(
    'mode'               => 'production',
    'debug'              => false, 
    'log.enable'         => true,
    'view'               => new HaangaView('./lib/Haanga', './tpl', './tpl_cache'),
    'templates.path'     => './tpl',
    'cookies.secret_key' => $COOKIES_SECRET_KEY,
);

$app = new Slim($slim_settings);
require 'app.php';
$app->run();

?>
