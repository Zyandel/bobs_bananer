<?php

session_start();
require_once("CDatabase.php");
require_once("CUser.php");
require_once("CFormCreator.php");

function print_r_pre($data)
{
	echo('<pre>');
	print_r($data);
	echo('</pre>');
}

function dd($data)
{
	print_r_pre($data);
	die();
}

function redirect(string $url)
{
	header("location: $url");
	die();
}

class CApp
{
	public function __construct()
	{
		$this->m_db = new CDatabase();
		$this->m_user = new CUser($this);
		$this->m_formCreator = new CFormCreator($this);
	}

	public function renderHeader(string $title)
	{
		?>
	<html>
			<head>
				<title><?php echo($title); ?></title>
				<link rel="stylesheet" href="style/general.css" />
				<script src="scripts/tools.js"></script>
			</head>
	<body>
		<img id="logo" src="images/Sjukhus.png"/>
        <div id="header">
            <nav>
                <div id="navbar">
                    <li><a href="symptomForm.php">Sjukformulär</a></li>
                    <?php
                    if($this->user()->isLoggedIn())    //Inloggad
                    {
                        $id = $_SESSION["userData"]["id"];
                        echo('<li class="navbarRight"><a href="register.php?id=' . $id . '">Min Profil</a></li>');
                        echo('<li class="navbarRight"><a href="logout.php">Logga ut</a></li>');
                        echo('<li class="navbarRight"><a href="contact.php">Boka tid</a></li>');
                    }
                    else    //Inte inloggad
                    {
                        echo('<li class="navbarRight"><a href="login.php">Logga in</a></li>');
                        echo('<li class="navbarRight"><a href="register.php">Registrera dig</a></li>');
                    }
                    ?>
                </div>
            </nav>
        </div>
			<main id="content">
			<h1><?php echo($title); ?></h1>
	<?php
	}

	public function renderFooter()
	{
		echo('
		</main>
            <div id="footer">
            
                <div id="left">             
                </div>

                <div id="right">
                </div>
            </div>
            <script src="scripts/tools.js"></script>
		</body>
		</html>');
	}

	public function loggedInOrAbort()
	{
		if(!$this->user()->isLoggedIn())
		{
			die("Du får inte vara här. Din mamma.");
		}
	}

	public function &db()	{ return $this->m_db; }
	public function &user()	{ return $this->m_user; }
	public function &form()	{ return $this->m_formCreator; }

	///////////////////////////////////
	private $m_db = null;
	private $m_user = null;
	private $m_formCreator = null;

};


$app = new CApp();

?>