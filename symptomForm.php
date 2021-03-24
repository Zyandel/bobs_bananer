<?php

require_once("inc/CApp.php");

?>

<?php
$app->renderHeader("Fill in your symptoms!");
?>

<?php

class CSymptomForm
{
	public function __construct(CApp &$app)
	{
		$this->m_app = $app;
	}

	public function __destruct()
	{
	}

    public function renderSymptomForm()
	{
		?>
        <form method="post">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName" name="firstName" placeholder="Your First Name..">

            <label for="lastName">Last Name</label>
            <input type="text" id="lastName" name="lastName" placeholder="Your Last Name..">

            <label for="email">Your E-Mail</label>
            <input type="email" id="email" name="email" placeholder="Your E-Mail..">

            <label for="phoneNumber">Phone Number</label>
            <input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Your Phone Number..">

            <label for="sleepHours">Amount of sleep:</label>
            <select id="sleepHours" name="sleepHours">
                <option value="9-12_hours">9-12 Hours</option>
                <option value="7-9_hours">7-9 Hours</option>
                <option value="5-7_hours">5-7 Hours</option>
                <option value="3-5_hours">3-5 Hours</option>
                <option value="0-3_hours">0-3 Hours</option>
            </select>

            <input type="submit" value="Submit">
        </form>

		<?php
	}

	private function validateForm(array $data)
	{
		$this->m_validationErrors = [];

		if(empty($data["firstName"]))
		{
			$this->m_validationErrors[] = "You need to enter a name!";
			return false;
		}

        if(empty($data["lastName"]))
		{
			$this->m_validationErrors[] = "You need to enter a last name!";
			return false;
		}

        if(empty($data["email"]))
		{
			$this->m_validationErrors[] = "You need to enter a email!";
			return false;
		}

        if(empty($data["phoneNumber"]))
		{
			$this->m_validationErrors[] = "You need to enter a phone number!";
			return false;
		}
		return true;
	}

	private function insert(array $data)
	{	
		$this->m_app->db()->insert("infoform", $data);
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

	///////////////////////////////////////////////////////////////
	// Variables
	private $m_validationErrors = []; 
	private $m_app = null;	
};

$news = new CSymptomForm($app);
$news->validateAndInsertForm();





$news->renderSymptomForm();
?>



<?php
$app->renderFooter();
?>