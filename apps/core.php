<?php
$curr_url = $app->request()->getResourceUri();
$app->view()->setData('curr_url', $curr_url);
?>
