<?php
$app->get('/', function() use ($app) {
    $app->redirect('home');
});

$app->get('/home', function() use ($app) {
	$auth=new AUTH($_SESSION['auth_uid']);
    $app->render('home.html', array (
        'breadcrumb_title' => '首頁',
		'login_log_err'=>$auth->get_log(0),
		'login_log_ok'=>$auth->get_log(1)
    ));
});
