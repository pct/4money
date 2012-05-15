<?php

$app->notFound(function() use ($app) {
	$file=dirname(__FILE__).'/..'.$app->request()->getResourceUri();
	if(file_exists($file)){
		$path=pathinfo($app->request()->getRootUri());
		$path=$path['dirname'];
		if($path=='/'||$path=='\\'){
			$path='';
		}
		$app->redirect($path.$app->request()->getResourceUri());
		$app->stop();
	}
    $app->render('404.html', array (
        'breadcrumb_title' => '404'
    ));
});
?>