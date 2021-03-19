<?php

require_once("inc/CApp.php");

?>

<?php
$app->renderHeader("Nyheter");
?>

<?php

class CNews
{
	public function __construct(CApp &$app)
	{
		$this->m_app = $app;
	}

	public function __destruct()
	{
		//echo("CNews Destruktor körs!");
	}

	public function renderForm()
	{
		?>
		<form method="post">

		<label for="subject">Rubrik:</label><br />
		<input type="text" name="subject" id="subject"/><br />

		<label for="text">Text:</label><br />
		<textarea name="text" id="text"></textarea><br />

		
		<input type="submit" value="Skicka" />
		</form>
		<?php
	}

	public function testValidation()
	{
		$start = microtime(true);
		$testCases = [];
		$testCases[] = ["expected"=>true, "text"=>"Hejsan", "subject"=>"Tobb"];
		$testCases[] = ["expected"=>false, "text"=>"Detta kommer faila", "subject"=>""];
		$testCases[] = ["expected"=>false, "text"=>"x", "subject"=>"Duger"];
		$testCases[] = ["expected"=>false, "text"=>"", "subject"=>""];
		

		foreach($testCases as $key=>$case)
		{
			if($this->validateForm($case) == $case["expected"])
			{
				echo("Test case " . ($key+1) . " succeeded<br />");
			}
			else
			{
				die("Test case " . ($key+1) . " failed");
			}
		}

		$end = microtime(true);
		$elapsed = $end - $start;
		$elapsed = $elapsed * 1000;
		$elapsed = $elapsed * 1000;

		echo("Elapsed time: " . $elapsed);
	}

	private function validateForm(array $data)
	{
		$this->m_validationErrors = [];
		if(strlen($data["text"]) < 3)
		{
			$this->m_validationErrors[] = "Ditt meddelande är för kort. Du måste ha minst 3 tecken.";
			return false;
		}

		if(empty($data["subject"]))
		{
			$this->m_validationErrors[] = "Du måste skriva en rubrik";
			return false;
		}
		return true;
	}

	private function insert(array $data)
	{	
		$data["date"] = time();
		$data["author"] = "Tobias";
		$this->m_app->db()->insert("news", $data);
	}

	public function validateAndInsertForm()
	{
		if(empty($_POST))
			return;
		
		if($this->validateForm($_POST))
		{
			$this->insert($_POST);	
		}
		else
		{
			echo("Det finns fel i inmatningen:");
			print_r($this->m_validationErrors);
		}
	}

	public function renderNewsItem(array $newsItem)
	{
		$dateText = date("Y-m-d, H:i", $newsItem["date"]);
		?>
		<div class="newsItem">
			<h2><?php echo($newsItem["subject"]); ?></h2>
			<div class="text"><?php echo(nl2br($newsItem["text"])); ?></div>
			<div class="footer">
				<div class="date"><?php echo($dateText); ?></div>
				<div class="author"><?php echo($newsItem["author"]); ?></div>
			</div>
		</div>

		<?php
	}

	/*public function getRandomizedNewsItem()
	{
		$subjects = ["En fin rubrik", "Idag har vi publicerat vår nya site.", "Idag var det 496 tusen år sedan King Kong dog.", "Min mamma är bättre än din mamma!"];
		$texts = [
			"Det här är världens bästa text", 
			"Sjukt kul med texter.",
			"Vem var det som kastade?", 
			"En något längre text, men ändå väldigt fin. Tror vi. Det är riktigt svårt att veta säkert. Sjukt underhållande med långa texter. Sa bamse."
		];

		$dates = [1611906404, 1211906404, 611906404, 2611906404];
		$authors = ["Tobias", "Kalle", "Amanda", "DJ.Kaluffs"];

		$subjectIndex = array_rand($subjects);
		$randomSubject = $subjects[$subjectIndex];

		$textIndex = array_rand($texts);
		$randomText = $texts[$textIndex];

		$dateIndex = array_rand($dates);
		$randomDate = $dates[$dateIndex];
		$dateText = date("Y-m-d, H:i", $randomDate);

		$authorIndex = array_rand($authors);
		$randomAuthor = $authors[$authorIndex];
		
		$newsItem = ["subject"=>$randomSubject, "text"=>$randomText, "date"=>$dateText, "author"=>$randomAuthor];	
		return $newsItem;
	}*/

	public function selectAndRenderAllNewsItems()
	{
		/*
		x Måste komma åt app
		x Måste komma åt db
		(Ansluta om inte det redan är gjort)
		*/


		//$query = "SELECT * FROM news";
		//$result = $this->m_app->db()->query($query);

		$result = $this->m_app->db()->selectAll("news");

		if($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				//var_dump($row);
				//echo("<pre>");
				//print_r($row);
				//echo("</pre>");
				$this->renderNewsItem($row);
			}
		}
		else
		{
			echo("Det finns inga nyheter");
		}

		//$this->renderNewsItem($this->getRandomizedNewsItem());
		//$this->renderNewsItem($this->getRandomizedNewsItem());
		//$this->renderNewsItem($this->getRandomizedNewsItem());
	}

	///////////////////////////////////////////////////////////////
	// Variables
	private $m_validationErrors = []; 
	private $m_app = null;	
};

$news = new CNews($app);
//$news->testValidation();
$news->validateAndInsertForm();
$news->selectAndRenderAllNewsItems();

//print_r_pre($_POST);


$news->renderForm();
?>



<?php
$app->renderFooter();
?>