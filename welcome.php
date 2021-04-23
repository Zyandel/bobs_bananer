<?php

require_once("inc/CApp.php");

$app->loggedInOrAbort();

?>

<?php
$app->renderHeader("Välkommen");

?>

<h2>Inloggad</h2>
<p>Välkommen, du är nu inloggad!</p>

<?php
$app->renderFooter();
?>