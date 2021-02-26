<?php



class CDatabase
{
    public function __construct()
    {

    $this->m_settings["server"] = "localhost";
    $this->m_settings["username"] = "root";
    $this->m_settings["password"] = "root";
    $this->m_settings["dbname"] = "site1";

    $this->m_connection = null;
    $this->connect();
    }

    public function connect()
    {
        $this->m_connection = new mysqli($this->m_settings["server"], $this->m_settings["username"], $this->m_settings["password"], $this->m_settings["dbname"]);

        if($this->m_connection->connect_error)
        {
            throw new Exception("Connection Failed: " . $this->m_connection->connect_error);
        }
    }

    public function query(string $query)
    {
        $result = $this->m_connection->query($query);

        if($result === false)
        {
            throw new Exception("Query error: " . $this->m_connection->error);
        }
        return $result;
    }

    public function selectAll(string $table)
    {
        $query = "SELECT * FROM " . $table;
        $result = $this->query($query);
        return $result;
    }

////////////////////////////////////////////////////
private $m_settings = [];
private $m_connection = null;
};


?>