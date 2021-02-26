<?php
require_once("inc/CApp.php");
$app->user()->handleLoginAttempt();
?>


<?php
$app->renderHeader("Login");
?>


<?php
$app->user()->renderLoginForm();
$app->renderFooter();
?>
