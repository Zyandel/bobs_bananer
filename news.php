<?php

require_once("inc/CApp.php");

?>

<?php
$app->renderHeader("News");
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

		<label for="subject">Subject:</label><br />
		<input type="text" name="subject" id="subject"/><br />

		<label for="text">Text:</label><br />
		<textarea name="text" id="text"></textarea><br />

		
		<input type="submit" value="Post" />
		</form>
		<?php
	}

	private function validateForm(array $data)
	{
		$this->m_validationErrors = [];
		if(strlen($data["text"]) < 3)
		{
			$this->m_validationErrors[] = "Your message is too short. You need atleast 3 characters!";
			return false;
		}

		if(empty($data["subject"]))
		{
			$this->m_validationErrors[] = "You need to write a header!";
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
			echo("There are errors in your input: ");
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

	public function selectAndRenderAllNewsItems()
	{
		$result = $this->m_app->db()->selectAll("news");

		if($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				$this->renderNewsItem($row);
			}
		}
		else
		{
			echo("There are no news!");
		}
	}

	///////////////////////////////////////////////////////////////
	// Variables
	private $m_validationErrors = []; 
	private $m_app = null;	
};

$news = new CNews($app);
$news->validateAndInsertForm();
$news->selectAndRenderAllNewsItems();



$news->renderForm();
?>



<?php
$app->renderFooter();
?>