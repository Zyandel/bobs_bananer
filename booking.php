<?php
require_once("inc/CApp.php");
$app->loggedInOrAbort();
$app->user()->handleBookingAttempt();
$app->renderHeader("Vänligen boka en tid för besök!");
?>


<?php
$app->user()->renderBookingForm();
$app->renderFooter();
?>