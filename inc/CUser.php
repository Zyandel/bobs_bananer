<?php

class CUser
{
	public function __construct(CApp &$app)
	{
		$this->m_app = $app;
	}

	public function isLoggedIn() 
	{
		return isset($_SESSION["loggedIn"]); 
	}

	public function insertWithPossibleLogin(array $data)
	{
		$id = $this->insert($data);
		if($id == 0)
		{

		}
		else
		{
			if(!$this->isLoggedIn())
			{
				$data["id"] = $id;
				$this->storeUserInSession($data);
				redirect("contact.php");
			}
		}
		return $id;
	}

	private function selectByUsername(string $username)
	{
		return $this->m_app->db()->selectByField("users", "username", $username);
	}

	private function selectByEmail(string $email)
	{
		return $this->m_app->db()->selectByField("users", "email", $email);
	}

	private function selectBySymptom(int $S1, int $S2, int $S3)
	{
		$query = "SELECT * FROM infoform WHERE symptom1=$S1, symptom2=$S2, symptom3=$S3";
		$result = $this->m_app->db()->query($query);
		if($result->num_rows == 0)
		{
			throw new Exception("Kombination Ej Hittad!");
		}

		$data = $result->fetch_assoc();
		
		return $data;
	}

	private function validate(array $data, int $id)
	{
		if($id == 0) // Alltså: Skapar user
		{
			if(!isset($data["username"]) || strlen($data["username"]) < 3)
			{
				$this->m_validationErrors[] = "Användarnamn saknas eller är för kort";
				return false;
			}

			if(!isset($data["email"]) || filter_var($data["email"], FILTER_VALIDATE_EMAIL) == false)
			{
				$this->m_validationErrors[] = "E-postadressen är felaktig";
				return false;
			}

			if(!isset($data["password"]) || strlen($data["password"]) < 6)
			{
				$this->m_validationErrors[] = "Lösenordet saknas eller är för kort";
				return false;
			}
		}
		else // Alltså redigerar user.
		{
			if(isset($data["password"]))
			{
				if($data["password"] == "")
				{
					
				}
				else if(strlen($data["password"]) < 6)
				{
					$this->m_validationErrors[] = "Lösenordet saknas eller är för kort!";
					return false;
				}
			}
		}

		if(isset($data["password"]))
		{
			if(!isset($data["password2"]))
			{
				$this->m_validationErrors[] = "Upprepning av lösenord saknas!";
				return false;
			}

			if($data["password"] != $data["password2"])
			{
				$this->m_validationErrors[] = "Lösenorden är inte identiska!";
				return false;
			}

		}


		$this->m_validationErrors = [];
		if(isset($data["username"]))
		{
			$user = $this->selectByUsername($data["username"]);

			if($user !== null && $user["id"] != $id) // if(is_array($user))
			{
				$this->m_validationErrors[] = "Användarnamnet är redan upptaget!";
				return false;
			}
		}

		if(isset($data["email"]))
		{
			$user = $this->selectByEmail($data["email"]);
			if($user !== null && $user["id"] != $id) 
			{
				$this->m_validationErrors[] = "E-Mail är redan upptagen!";
				return false;
			}
		}

		return true;
	}

	public function insert(array $data)
	{
		if($this->validate($data, 0))
		{
			if(isset($data["password2"]))
				unset($data["password2"]);

			$data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
			return $this->m_app->db()->insert("users", $data);
		}
		else
		{
			echo("Det uppstod ett fel: ");
			print_r($this->m_validationErrors);
		}
		return 0;
	}

	public function updateById(array $data, int $id)
	{
		if($this->validate($data, $id))
		{
			if(isset($data["password2"]))
				unset($data["password2"]);

			if($data["password"] == "")
			{
				unset($data["password"]);
			}
			else
			{
				$data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
			}

			return $this->m_app->db()->updateById("users", $data, $id);
		}
		else
		{
			echo("Det uppstod ett fel: ");
			print_r($this->m_validationErrors);
		}
		
		
		return 0;
	}
	
	public function selectById(int $id)
	{
		$query = "SELECT * FROM users WHERE id=$id";
		$result = $this->m_app->db()->query($query);
		if($result->num_rows == 0)
		{
			throw new Exception("Användaren kunde inte hittas!");
		}

		$data = $result->fetch_assoc();
		
		return $data;
	}

	private function storeUserInSession(array $userData)
	{
		$_SESSION["loggedIn"] = true;
		$_SESSION["userData"] = $userData;
	}

	private function findAndLoginUser(string $username, string $password)
	{
		$userData = $this->selectByUsername($username);

		if(is_null($userData))
		{
			throw new Exception("Felaktig inloggning!");
		}
		else // Användare hittad!
		{
			if(password_verify($password, $userData["password"]))
			{
				$this->storeUserInSession($userData);
				redirect("welcome.php");
			}
			else
			{
				throw new Exception("Felaktigt Lösenord!");
			}
		}
	}

	public function handleLoginAttempt()
	{
		if(!empty($_POST))
		{
			$username = $_POST["username"];
			$password = $_POST["password"];

			$this->findAndLoginUser($username, $password);
		}
	}

	public function findAndDirectByFormCombo(int $S1, int $S2, int $S3)
	{
		///////////////Case 1/////////////////
		if($_POST["symptom1"] == 1 && $_POST["symptom2"] == 1 && $_POST["symptom3"] == 3)
		{
			redirect("logout.php");
		}


		///////////////Case 2/////////////////
		if($_POST["symptom1"] == 3 && $_POST["symptom2"] == 1 && $_POST["symptom3"] == 2)
		{
			redirect("logout.php");
		}


		///////////////Case 3/////////////////
		if($_POST["symptom1"] == 2 && $_POST["symptom2"] == 2 && $_POST["symptom3"] == 1)
		{
			redirect("logout.php");
		}


		///////////////Case 4/////////////////
		if($_POST["symptom1"] == 3 && $_POST["symptom2"] == 3 && $_POST["symptom3"] == 1)
		{
			redirect("logout.php");
		}
		else
		{
			$this->validateAndInsertForm();	
			redirect("contact.php");
		}

	}

	public function handleFormDirectory()
	{
		if(!empty($_POST))
		{
			$S1 = $_POST["symptom1"];
			$S2 = $_POST["symptom2"];
			$S3 = $_POST["symptom3"];

			$this->findAndDirectByFormCombo($S1, $S2, $S3);
		}
	}

	public function logout()
	{
		unset($_SESSION["loggedIn"]);
		unset($_SESSION["userData"]);
	}

	public function renderLoginForm()
	{
		?>
		<form method="post" autocomplete="off">
			<label for="username">Användarnamn:</label>
			<input type="text" name="username" autocomplete="off"/> <br />

			<label for="password">Lösenord:</label>
			<input type="password" name="password" /><br />


			<input type="submit" value="Logga in" />

		</form>
		<?php
	}

	public function renderContactForm()
	{
		?>
		<form method="post">
			<label for="firstName">Förnamn:</label>
			<input type="text" id="firstName" name="firstName" placeholder="Ditt Förnamn.."></br>

			<label for="lastName">Efternamn:</label>
			<input type="text" id="lastName" name="lastName" placeholder="Ditt Efternamn.."></br>

			<label for="email">Din E-Mail:</label>
			<input type="email" id="email" name="email" placeholder="Din E-Mail.."></br>

			<label for="phoneNumber">Telefon Nummer:</label>
			<input type="tel" id="phoneNumber" name="phoneNumber" placeholder="Ditt telefonnummer.."></br>

			<input type="submit" value="Boka">
		</form>
		<?php
	}




////////////////////////////Symptom Form////////////////////////////
	public function renderSymptomForm()
	{
		?>
        <form method="post">

            <label for="symptom1">Symtom 1:</label>
            <select id="symptom1" name="symptom1">
                <option value="1">Snuva/nästäppa</option>
                <option value="2">Bröstmsmärta</option>
                <option value="3">Trötthet</option>

            </select></br>

			<label for="symptom2">Symtom 2:</label>
            <select id="symptom2" name="symptom2">
                <option value="1">Hosta</option>
                <option value="2">Andningssvårigheter</option>
                <option value="3">Ont i magen</option>

            </select></br>

			<label for="symptom3">Symtom 3:</label>
            <select id="symptom3" name="symptom3">
				<option value="1">Illamående/Yrsel</option>
                <option value="2">Feber</option>
                <option value="3">Ont i halsen</option>

            </select></br>

			<label for="sleepHours">Mängd Sömn:</label>
            <select id="sleepHours" name="sleepHours">
                <option value="9-12_hours">9-12 Timmar</option>
                <option value="7-9_hours">7-9 Timmar</option>
                <option value="5-7_hours">5-7 Timmar</option>
                <option value="3-5_hours">3-5 Timmar</option>
                <option value="0-3_hours">0-3 Timmar</option>
            </select></br>


            <input type="submit" value="Skicka">
        </form>

		<?php
	}

	private function validateForm(array $data)
	{
		$this->m_validationErrors = [];

		if(empty($data["symptom1"]))
		{
			$this->m_validationErrors[] = "Du behöver välja symtom!";
			return false;
		}

        if(empty($data["symptom2"]))
		{
			$this->m_validationErrors[] = "Du behöver välja symtom!";
			return false;
		}

        if(empty($data["symptom3"]))
		{
			$this->m_validationErrors[] = "Du behöver välja symtom!";
			return false;
		}

        if(empty($data["sleepHours"]))
		{
			$this->m_validationErrors[] = "Du behöver ange mängden sömn du får!";
			return false;
		}
		return true;
	}

	private function insertInfoForm(array $data)
	{	
		$this->m_app->db()->insert("infoform", $data);
	}

	public function validateAndInsertForm()
	{
		if(empty($_POST))
			return;
		
		if($this->validateForm($_POST))
		{
			$this->insertInfoForm($_POST);	
		}
		else
		{
			echo("There are errors in your input: ");
			print_r($this->m_validationErrors);
		}
	}

	///////////////// Member variables ///////////////////////
	private $m_app = NULL;
	private $m_validationErrors = [];

};


?>