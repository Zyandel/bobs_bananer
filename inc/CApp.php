<?php

session_start();
require_once("CDatabase.php");
require_once("CUser.php");

function print_r_pre($data)
{
    echo('<pre>');
    print_r($data);
    echo('</pre>');
}

class CApp
{
    public function __construct()
    {
        $this->m_db = new CDatabase();
        $this->m_user = new CUser($this);
    }

    public function renderHeader(string $title)
    {
    ?>
        <html>
        <head>
        <title><?php echo($title)?></title>
        <link rel="stylesheet" type="text/css" href="style/general.css"/>
        </head>
    
        <body>
        <div id="header">
            <image src="images/Sjukhus.png"></image>
            <nav>
                <ul>
                    <li><a class="active" href="./">Hem</a></li>
                    <li><a href="news.php">Nyheter</a></li>
                    <?php
                        if($this->user()->isLoggedIn())    //Inloggad
                        {
                            echo('<li><a href="logout.php">Logout</a></li>');
                        }
                        else    //Inte inloggad
                        {
                            echo('<li><a href="login.php">Login</a></li>');
                        }
                    ?>
                    <li class="dropdown">
                        <a class="dropbutton">Om Oss</a>
                        <div class="dropdown-content">
                            <a href="">Öppettider</a>
                            <a href="">Vägbeskrivning</a>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
        
        
    <?php
    }


    public function renderFooter()
    {
        ?>
            <div id="footer"> 
                Footer 
            </div>
        </body>
        </html>
        <?php
    }

    public function loggedInOrAbort()
    {
        if(!$this->user()->isLoggedIn()) 
        {
            die("You are not allowed to be here! Joe Mama :|");
        }
    }

public function &db()    {return $this->m_db;}
public function &user()    {return $this->m_user;}

//////////////////////////////////////
private $m_db = null;
private $m_user = null;
};

$app = new CApp();

?>