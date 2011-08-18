<?php

$app->notFound(function() use ($app) {
    $app->render('404.html', array (
        'breadcrumb_title' => '404'
    ));
});
