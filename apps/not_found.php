<?php

$app->notFound(function() use ($app) {
	$file=dirname(__FILE__).'/..'.$app->request()->getResourceUri();
	if(file_exists($file)){
		$app->redirect(dirname($app->request()->getRootUri()).$app->request()->getResourceUri());
		$app->stop();
	}
    $app->render('404.html', array (
        'breadcrumb_title' => '404'
    ));
});
