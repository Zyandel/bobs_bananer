<?php
require_once("inc/CApp.php");
$app->user()->handleFormDirectory();
$app->renderHeader("Fyll i dina symtom!");
?>


<?php
$app->user()->renderSymptomForm();
$app->renderFooter();
?>