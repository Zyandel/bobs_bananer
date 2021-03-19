<?php

require_once("inc/CApp.php");

?>

<?php
$app->renderHeader("Sandbox");
?>


<p>Experimentsidan deluxe. Kärlek.</p>

<?php

$user = ["username"=>"Kalasklasxy", "email"=>"tob@tob.tobxx", "password"=>"hoooooo"];
$newsItem = ["subject"=>"Autonyhet", "text"=>"Nyhetstext. Wow.", "date"=>2323, "author"=>"Tobbe"];

//$app->db()->insert("users", $user);

$app->user()->insert($user);


$app->renderFooter();
?>