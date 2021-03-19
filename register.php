<?php

require_once("inc/CApp.php");



$id = isset($_GET["id"]) ? $_GET["id"] : 0;
$isCreating = $id == 0; // true om id är 0, false annars.

$form = $app->form();
if($id > 0) // Alltså redigerar en user
{
	$app->loggedInOrAbort();
	$userData = $app->user()->selectById($id);

	$userData = array_merge($userData, $_POST);
	$form->presetData($userData);
}
else
{
	$form->presetData($_POST);
}

if(!empty($_POST))
{
	$data = $_POST;

	

	if($isCreating)
	{
		//$id = $app->user()->insert($data);
		$id = $app->user()->insertWithPossibleLogin($data);

		if($id != 0)
			redirect("register.php?id=$id");
	}
	else
	{
		$result = $app->user()->updateById($data, $id);

		if($result != 0)
			redirect("register.php?id=$id");
	}
	
}
?>

<?php
if($isCreating)
{
	$title = "Registrera dig";
}
else
{
	$title = "Ändra inställningar för " . $userData["username"];
}
$app->renderHeader($title);

$form->open();

$form->createText("username", "Användarnamn");
$form->createEmail("email", "E-post");
$form->createPassword("password", "Lösenord");
$form->createPassword("password2", "Upprepa lösenord");

$form->createSubmit($isCreating ? "Registrera dig" : "Spara inställningar");

$form->close();
?>



<?php
$app->renderFooter();
?>