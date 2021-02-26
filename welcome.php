<?php
require_once("inc/CApp.php");

$app->loggedInOrAbort();

?>


<?php
$app->renderHeader("Welcome!");
print_r_pre($_SESSION);
?>

<h2>To the mortal realm!</h2>

<?php
$app->renderFooter();
?>
