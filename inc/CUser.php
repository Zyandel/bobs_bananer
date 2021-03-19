<?php

class CUser
{
	public function __construct(CApp &$app)
	{
		$this->m_app = $app;
	}

	public function isLoggedIn() 
	{
		/*if(isset($_SESSION["loggedin"]))
		{
			return true;
		}
		else
		{
			return false;
		}*/
		return isset($_SESSION["loggedIn"]); // samma sak som ovanför
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
				redirect("welcome.php");
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
					$this->m_validationErrors[] = "Lösenordet saknas eller är för kort";
					return false;
				}
			}
		}

		if(isset($data["password"]))
		{
			if(!isset($data["password2"]))
			{
				$this->m_validationErrors[] = "Upprepning av lösenord saknas";
				return false;
			}

			if($data["password"] != $data["password2"])
			{
				$this->m_validationErrors[] = "Lösenorden är inte identiska";
				return false;
			}

		}


		$this->m_validationErrors = [];
		if(isset($data["username"]))
		{
			$user = $this->selectByUsername($data["username"]);

			if($user !== null && $user["id"] != $id) // if(is_array($user))
			{
				$this->m_validationErrors[] = "Användarnamnet är upptaget";
				return false;
			}
		}

		if(isset($data["email"]))
		{
			$user = $this->selectByEmail($data["email"]);
			if($user !== null && $user["id"] != $id) 
			{
				$this->m_validationErrors[] = "E-postadressen är upptagen";
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
			echo("Det finns fel i inmatningen:");
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
			echo("Det finns fel i inmatningen:");
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
			throw new Exception("Användaren kunde inte hittas");
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
			throw new Exception("Felaktig inloggning");
		}
		else // Användare hittad!
		{
			//print_r_pre($userData);
			//die();
			if(password_verify($password, $userData["password"]))
			{
				$this->storeUserInSession($userData);
				redirect("welcome.php");
			}
			else
			{
				die("Felaktigt lösenord");
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

	public function logout()
	{
		unset($_SESSION["loggedIn"]);
		unset($_SESSION["userData"]);
	}

	public function renderLoginForm()
	{
		?>
		<form method="post" autocomplete="off">

			Användarnamn: <input type="text" name="username" autocomplete="off"/> <br />

			Lösenord: <input type="password" name="password" /><br />


			<input type="submit" value="Logga in" />

		</form>
		<?php
	}

	public function renderSymptomForm()
	{
		?>
		<form id="regForm" action="/action_page.php">
			<h1>Register:</h1>

			<div class="tab">Name:
				<p><input placeholder="First name..." oninput="this.className = ''" name="fname"></p>
				<p><input placeholder="Last name..." oninput="this.className = ''" name="lname"></p>
			</div>

			<div class="tab">Contact Info:
				<p><input placeholder="E-mail..." oninput="this.className = ''" name="email"></p>
				<p><input placeholder="Phone..." oninput="this.className = ''" name="phone"></p>
			</div>

			<div class="tab">Birthday:
				<p><input placeholder="dd" oninput="this.className = ''" name="dd"></p>
				<p><input placeholder="mm" oninput="this.className = ''" name="nn"></p>
				<p><input placeholder="yyyy" oninput="this.className = ''" name="yyyy"></p>
			</div>

			<div class="tab">Login Info:
				<p><input placeholder="Username..." oninput="this.className = ''" name="uname"></p>
				<p><input placeholder="Password..." oninput="this.className = ''" name="pword" type="password"></p>
			</div>

			<div id="Sub_Button">
				<div>
				<button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
				<button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
				</div>
			</div>

			<div style="text-align:center;margin-top:40px;">
				<span class="step"></span>
				<span class="step"></span>
				<span class="step"></span>
				<span class="step"></span>
			</div>
		</form>

		<?php
	}

	///////////////// Member variables ///////////////////////
	private $m_app = NULL;
	private $m_validationErrors = [];

};


?>