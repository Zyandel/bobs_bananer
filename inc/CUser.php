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

    private function findAndLoginUser(string $username, string $password)
    {
        $query = "SELECT * FROM USERS WHERE username='$username' AND password='$password'";
        $result = $this->m_app->db()->query($query);
        if($result->num_rows == 0)
        {
            die("Mamma");
        }
        else
        {
            $_SESSION["loggedIn"] = true;
            $_SESSION["user"] = $username;
            header("location: welcome.php");
            die();
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
    }

    public function renderLoginForm()
    {
        ?>

        <form method="post">
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username" /><br />

            <label for="password">Password:</label><br />
            <input type="password" name="password" id="password" /><br />

            <input type="submit" value="Login" />
        </form>

        <?php
    }



    /////////////////////////// Medlems Variabler ////////////////////
    private $m_app = NULL;
};


?>