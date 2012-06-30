<?php

//Twig Filter
$app->view()->getEnvironment()->addFilter('money',new Twig_Filter_Function('number_format'));
$app->view()->getEnvironment()->addFilter('substr',new Twig_Filter_Function('substr'));

require 'apps/core.php';
require 'apps/index.php';
require 'apps/option.php';
require 'apps/quotation.php';
require 'apps/customer.php';
require 'apps/not_found.php';
require 'apps/session.php';
require 'apps/profile.php';
require 'apps/user_mgr.php';
?>