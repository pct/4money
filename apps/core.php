<?php
$curr_url = $app->request()->getResourceUri();
$doc_root = $app->request()->getRootUri();
$app->view()->setData('curr_url', $curr_url);
$app->view()->setData('doc_root', $doc_root);
$app->view()->setData('link', '');
?>
