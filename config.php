<?php

$DB_HOST = 'localhost';
$DB_NAME = '4money';
$DB_USER = 'root';
$DB_PASS = '';

$COOKIE_NAME = 'money_session';
$COOKIES_SECRET_KEY = 'PLEASE CHANGE THIS';
$WKHTMLTOPDF_BIN_PATH = '/usr/local/bin/wkhtmltopdf'; # if you need pdf support

/*
    安全層級
	0 無認證,所有功能都是公開的的
	1 使用者認證,報價單是公開的
	2 使用者認證,報價單是私有的 (設定 $QUOTATION_ADMIN可存取所有報價單)
*/
$SECURITY_LEVEL = 0;
$SYSTEM_ADMIN = array('admin');
$QUOTATION_ADMIN = array();

?>