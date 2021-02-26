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
        //echo("CNews Destructor körs!");
    }
    public function renderForm()
    {
        ?><form method="post">

        <label for="subject">Rubrik:</label><br>
        <input type="text" name="subject" id="subject" /><br />

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
        $testCases[] = ["expected"=>true, "text"=>"Hejsan", "subject"=>"Tobbe"];
        $testCases[] = ["expected"=>false, "text"=>"Detta kommer faila", "subject"=>""];
        $testCases[] = ["expected"=>false, "text"=>"X", "subject"=>"Duger"];

        foreach($testCases as $key=>$case)
        {
                if($this->validateForm($case) == $case["expected"])
            {
                echo("Test case " . ($key+1) . " succeeded!<br />");
            }
            else
            {
                die("Test case " . ($key+1) . " failed!");
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
            $this->m_validationErrors[] = "Ditt meddelande är för kort, du måste ha minst 3 tecken";
            return false;
        }

        if(empty($data["subject"]))
        {
            $this->m_validationErrors[] = "Du måste skriva en rubrik";
            return false;
        }
        return true;
    }

    private function storeForm(array $data)
    {
        $data["date"] = time();
        $data["author"] = "Byggare Bob";
        $query = "INSERT INTO `news` (`subject`, `text`, `date`, `author`) VALUES ('" .  $data["subject"] . "', '" . $data["text"] . "', '" . $data["date"] . "', '" . $data["author"] . "');";

        $this->m_app->db()->query($query);
    }

    public function validateAndStoreForm()
    {
        if(empty($_POST))
            return;

        if($this->validateForm($_POST))
        {
        $this->storeForm($_POST);
        }
        else
        {
            echo('Det finns fel i inmatningen');
            print_r($this->m_validationErrors);
        }
    }

    public function renderNewsItem(array $newsItem)
    {
        $dateText = date("Y-m-d // H:i e", $newsItem["date"]);
        ?>
        <div class="newsItem">
            <h2>
            <?php echo($newsItem["subject"]); ?>
            </h2>

            <div class="text">
            <?php echo($newsItem["text"]); ?>
            </div>

            <div class="date">
            <?php echo($dateText); ?>
            </div>

            <div class="author">
            <?php echo($newsItem["author"]); ?>
            </div>
        </div>
        <?php
    }

    public function selectAndRenderAllNewsItems()
    {   
        /*$query = "SELECT * FROM news";
        $result = $this->m_app->db()->query($query);*/

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
            echo("There are no more news to be shown!");
        }
    }

    ///////////////////////////////////////
    // Variables
    private $m_validationErrors = [];
    private $m_app = null;

};


$news = new CNews($app);

$news->validateAndStoreForm();
$news->selectAndRenderAllNewsItems();
$news->renderForm();

?>

<?php
$app->renderFooter();
?>
