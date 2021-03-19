<?php

require_once("inc/CApp.php");

?>


<?php
$app->renderHeader("Välkommen");
?>


<?php
$app->user()->renderSymptomForm();
$app->renderFooter();
?>
