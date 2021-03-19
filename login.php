<?php


require_once("inc/CApp.php");

$app->user()->handleLoginAttempt();

$app->renderHeader("Logga in");

?>

<p>Logga in ditt jäkla snömonster.</p>

<?php

$app->user()->renderLoginForm();
$app->renderFooter();
?>