<?php


require_once("CDatabase.php");

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
            <nav>
                <ul>
                    <li><a href="./">Startsida</a></li>
                    <li><a href="contact.php">Kontakt</a></li>
                    <li><a href="news.php">News</a></li>
                    <li><a href="dbtest.php">DB test</a></li>
                </ul>
            </nav>
        </div>
    
        <main id="content">
        <h1><?php echo($title)?></h1>
    <?php
    }

    public function renderFooter()
{
    echo
    ('
    </main>
    </body>
    </html>
    ');
}

public function &db()    {return $this->m_db;}

//////////////////////////////////////
private $m_db = null;
};

$app = new CApp();

?>