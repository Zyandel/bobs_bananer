<?php

require_once("inc/CApp.php");

$app->loggedInOrAbort();

?>

<?php
$app->renderHeader("Dina Bokningar!");
?>

<?php

class CBookings
{
	public function __construct(CApp &$app)
	{
		$this->m_app = $app;
	}

	public function __destruct()
	{
		//echo("CNews Destruktor körs!");
	}

	public function renderBookings(array $bookingItem)
	{
		$dateText = date("Y-m-d, H:i", $bookingItem["date"]);
		?>
		<div class="bookingItems">
			<h3><?php echo("Bokning för: " . $bookingItem["firstName"] ." ". $bookingItem["lastName"]); ?></h3>
			<div class="text"><?php echo(nl2br("Angiven E-mail: ".$bookingItem["email"])); ?></div>
			<div class="date"><?php echo("Bokades den: ". $dateText); ?></div>
			<div class="author"><?php echo("Boknings ID: " . $bookingItem["id"]); ?></div>
		</div>

		<?php
	}

	public function selectAndRenderAllBookingItems()
	{

		$result = $this->m_app->db()->selectAll("bookingform");

		if($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				$this->renderBookings($row);
			}
		}
		else
		{
			echo("Det finns inga bokningar");
		}
	}

	///////////////////////////////////////////////////////////////
	// Variables
	private $m_validationErrors = []; 
	private $m_app = null;	
};

$bookings = new CBookings($app);
$bookings->selectAndRenderAllBookingItems();
?>



<?php
$app->renderFooter();
?>