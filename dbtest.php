<?php

require_once("inc/CApp.php");


$server = "localhost";
$username = "root";
$password = "root";
$dbname = "site1";

$conn = new mysqli($server, $username, $password, $dbname);

if($conn->connect_error)
{
    throw new Exception("Connection Failed: " . $conn->connect_error);
}



$query = "SELECT * FROM news";
$result = $conn->query($query);

if($result === false)
{
    throw new Exception("Query error: " . $conn->error);
}

if($result->num_rows > 0)
{
    echo("There are more existing rows!");
    while($row = $result->fetch_assoc())
    {
        echo("<pre>");
        print_r($row);
        echo("</pre>");
    }
}
else
{
    echo("There are no more rows to be shown!");
}

?>


<?php
$app->renderHeader("DB test");
?>

<p>Här sker allt som har med hinkar att göra</p>

<?php
$app->renderFooter();
?>
