<?php

require_once("inc/CApp.php");

$app->loggedInOrAbort();

?>

<?php
$app->renderHeader("Välkommen");

//print_r_pre($_SESSION);
?>

<h2>Inloggad</h2>
<p>Välkommen till den inloggade delen av denna fantastiska site. Puss.</p>

<?php
$app->renderFooter();
?>