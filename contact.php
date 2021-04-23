<?php
require_once("inc/CApp.php");
?>

<?php
$app->renderHeader("Kontakta oss");
?>

<h2>Utifrån dina inmatade symtom så ber vi dig att skapa ett konto på sidan och boka ett möte</h2>

<?php
$app->user()->renderContactForm();
$app->renderFooter();
?>