<?php

require_once("inc/CApp.php");

$app->user()->logout();

?>

<?php
$app->renderHeader("Logged Out!");
?>

<p>You are no longer logged in!</p>

<?php
$app->renderFooter();
?>