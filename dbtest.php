<?php

require_once("inc/CApp.php");


$server = "localhost";
$username = "root";
$password = "root";
$dbname = "site1";

$conn = new mysqli($server, $username, $password, $dbname);

if($conn->connect_error)
{
	throw new Exception("Connection failed: " . $conn->connect_error);
}



$query = "SELECT * FROM news WHERE 1";
$result = $conn->query($query);

if($result === false)
{
	throw new Exception("Query error: " . $conn->error);
}

if($result->num_rows > 0)
{
	echo("Rader finns");
	while($row = $result->fetch_assoc())
	{
		//var_dump($row);
		echo("<pre>");
		print_r($row);
		echo("</pre>");
	}
}
else
{
	echo("Det finns inget att visa");
}


?>

<?php
$app->renderHeader("DB test");
?>


<p>Den här sidan handlar om allt som är roligt.</p>

<?php
$app->renderFooter();
?>