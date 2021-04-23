<?php

require_once("inc/CApp.php");

$app->user()->logout();

?>

<?php
$app->renderHeader("Du är utloggad!");
?>

<p>You are no longer logged in!</p>

<?php
$app->renderFooter();
?>