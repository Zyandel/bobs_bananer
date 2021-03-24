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
                <div class="navbar">
                    <li><a href="./">Home</a></li>
                    <li><a href="symptomForm.php">Form</a></li>
                    <li><a href="news.php">News</a></li>
                    <li><a href="register.php">Register</a></li>
                    <?php
                    if($this->user()->isLoggedIn())    //Inloggad
                    {
                        $id = $_SESSION["userData"]["id"];
                        echo('<li><a href="register.php?id=' . $id . '">My Profile</a></li>');
                        echo('<li><a href="logout.php">Logout</a></li>');
                    }
                    else    //Inte inloggad
                    {
                        echo('<li><a href="login.php">Login</a></li>');
                    }
                    ?>
                    <div class="dropdown">
                        <button class="dropbtn">More Categories
                        <i class="fa fa-caret-down"></i>
                        </button>
                        <div class="dropdown-content">
                            <div class="header">
                                <h2>Mega Menu</h2>
                        </div>
                        <div class="row">
                            <div class="column">
                                <h3>Category 1</h3>
                                <a href="#">Link 1</a>
                                <a href="#">Link 2</a>
                                <a href="#">Link 3</a>
                            </div>
                            <div class="column">
                                <h3>Category 2</h3>
                                <a href="#">Link 1</a>
                                <a href="#">Link 2</a>
                                <a href="#">Link 3</a>
                            </div>
                            <div class="column">
                                <h3>Category 3</h3>
                                <a href="#">Link 1</a>
                                <a href="#">Link 2</a>
                                <a href="#">Link 3</a>
                            </div>
                        </div>
                    </div>
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
			</div>
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