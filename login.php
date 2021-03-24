<?php


require_once("inc/CApp.php");

$app->user()->handleLoginAttempt();

$app->renderHeader("Log In");

?>

<p>Ey you snowmonster, log in!</p>

<?php

$app->user()->renderLoginForm();
$app->renderFooter();
?>