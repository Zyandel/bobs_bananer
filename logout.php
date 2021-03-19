<?php

require_once("inc/CApp.php");

$app->user()->logout();

?>

<?php
$app->renderHeader("Utloggad");
?>

<p>Du är nu utloggad.</p>

<?php
$app->renderFooter();
?>