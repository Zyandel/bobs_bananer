<?php
require_once("inc/CApp.php");
$app->user()->handleLoginAttempt();
$app->renderHeader("Logga in");
?>


<?php
$app->user()->renderLoginForm();
$app->renderFooter();
?>