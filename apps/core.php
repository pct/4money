<?php
$headers = $app->request()->headers();
$curr_url = $app->request()->getResourceUri();
$doc_root = $app->request()->getRootUri();
$protocal = (isset($_SERVER['HTTPS'])) ? 'https' : 'http';
$full_doc_root = $protocal.'://'.$headers['host'];

// for apps
$app->config('curr_url', $curr_url);
$app->config('doc_root', $doc_root);
$app->config('full_doc_root', $full_doc_root);

// for tpls
$app->view()->setData('curr_url', $curr_url);
$app->view()->setData('doc_root', $doc_root);
$app->view()->setData('full_doc_root', $full_doc_root);
$app->view()->setData('link', '');
?>
