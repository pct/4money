<?php
require 'vendor/autoload.php';
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

$app = new \Slim\Slim(array(
    'mode'               => 'production',
    'debug'              => false, 
    'log.enable'         => true,
    'templates.path'     => 'tpl',
));

\Slim\Extras\Views\Twig::$twigOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath('./tpl_cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);
$app->view(new \Slim\Extras\Views\Twig());

require 'app.php';
$app->run();

?>
