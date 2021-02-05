<?php

require_once("inc/CApp.php");

?>


<?php
$app->renderHeader("Kontakta oss");
?>

<?php


class CContact
{
    public function renderForm()
    {
        ?><form method="post">

        <label for="message">Meddelande</label><br />
        <textarea name="message" id="message"></textarea><br />

        <label for="name">Namn:</label><br>
        <input type="text" name="name" id="name" /><br />

        <label for="phone">Telefonnummer:</label><br>
        <input type="tel" name="phone" id="phone" /><br />

        <label for="email">E-post:</label><br />
        <input type="email" name="email" id="email" /><br />

        <input type="submit" value="Skicka" />

        </form>
        <?php
    }

    public function testValidation()
    {
        $start = microtime(true);
        $testCases = [];
        $testCases[] = ["expected"=>true, "message"=>"Hejsan", "name"=>"Tobbe"];
        $testCases[] = ["expected"=>false, "message"=>"Detta kommer faila", "name"=>""];
        $testCases[] = ["expected"=>false, "message"=>"X", "name"=>"Duger"];

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
        if(strlen($data["message"]) < 3)
        {
            $this->m_validationErrors[] = "Ditt meddelande är för kort, du måste ha minst 3 tecken";
            return false;
        }

        if(empty($data["name"]))
        {
            $this->m_validationErrors[] = "Du måste skriva ett namn";
            return false;
        }
        return true;
    }

    private function storeForm()
    {
        die("storeForm() är inte implementerad än.");
    }

    public function validateAndStoreForm()
    {
        if(empty($_POST))
            return;

        if($this->validateForm($_POST))
        {
        $this->storeForm();
        }
        else
        {
            echo('Det finns fel i inmatningen');
            print_r($this->m_validationErrors);
        }
    }

    ///////////////////////////////////////
    // Variables

    private $m_validationErrors = [];

};


$contact = new CContact();
$contact->testValidation();



echo('<pre>');
print_r($_POST);
echo('</pre>');

$contact->validateAndStoreForm();
$contact->renderForm();

?>

<?php
$app->renderFooter();
?>
