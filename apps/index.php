<?php
$app->get('/', function() use ($app) {
    $app->redirect('home');
});

$app->get('/home', function() use ($app) {
    $app->render('home.html', array (
        'breadcrumb_title' => '首頁'
    ));
});
