<?php

require_once("inc/CApp.php");

$app->user()->logout();

?>


<?php
$app->renderHeader("Goodbye!");
?>

<h2>You have been banished from the mortal realm!</h2>

<?php
$app->renderFooter();
?>
