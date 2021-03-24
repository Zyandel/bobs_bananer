<?php

class CDatabase
{
	public function __construct()
	{
		$this->m_settings["server"] = "localhost";
		$this->m_settings["username"] = "root";
		$this->m_settings["password"] = "root";
		$this->m_settings["dbname"] = "sjukhus";

		$this->m_connection = null;
		$this->connect();
	}

	public function connect()
	{
		$this->m_connection = new mysqli($this->m_settings["server"], $this->m_settings["username"], $this->m_settings["password"], $this->m_settings["dbname"]);

		if($this->m_connection->connect_error)
		{
			throw new Exception("Connection failed: " . $this->m_connection->connect_error);
		}
	}

	public function query(string $query)
	{
		$result = $this->m_connection->query($query);

		if($result === false)
		{
			echo($query);
			throw new Exception("Query error: " . $this->m_connection->error);
		}
		return $result;
	}

	public function insert(string $table, array $data)
	{
		if(empty($data))
		{
			throw new Exception("No data provided. Array is empty");
		}
		$query = "INSERT INTO $table (";
		$counter = 1;

		foreach($data as $field=>$value)
		{
			$field = $this->escape($field);
			$query .= "`$field`";
			
			if($counter < count($data))
			{
				$query .= ", ";
			}
			$counter++;

		}
		$query .= ") VALUES (";

		$counter = 1;
		foreach($data as $field=>$value)
		{
			$value = $this->escape($value);
			$query .= "'$value'";
			
			if($counter < count($data))
			{
				$query .= ", ";
			}
			$counter++;

		}

		$query .= ")";
		$result = $this->query($query);
		$id = $this->getInsertId();
		return $id;
	}

	public function updateById(string $table, array $data, int $id)
	{
		$query = "UPDATE $table SET ";

		$counter = 1;
		foreach($data as $field=>$value)
		{
			$value = $this->escape($value);
			$field = $this->escape($field);
			$query .= $field;
			$query .= "=";
			$query .= "'$value'";

			if($counter < count($data))
			{
				$query .= ", ";
			}
			$counter++;
		}

		$query .= " WHERE id=" . $id;
		$result = $this->query($query);

		return $result;
	}

	public function selectByField(string $table, string $field, string $value)
	{
		$value = $this->escape($value);

		$query = "SELECT * FROM $table WHERE $field='$value'";
		$result = $this->query($query);
		
		if($result->num_rows == 0)
		{
			return null;
		}

		return $result->fetch_assoc();
	}

	public function selectAll(string $table)
	{
		$query = "SELECT * FROM " . $table;
		$result = $this->query($query);
		return $result;
	}

	public function getInsertId()
	{
		return $this->m_connection->insert_id;
	}

	public function escape(string $text)
	{
		/*$pos = strpos($text, '<');
		if($pos !== false)
		{
			die("Pls me no hacking, me tired");
		}

		$pos = strpos($text, '>');
		if($pos !== false)
		{
			die("Pls me no hacking, me tired");
		}*/
		$text = htmlspecialchars($text);

		return $this->m_connection->real_escape_string($text);
	}

	
	///////////////////////////////
	private $m_settings = [];
	private $m_connection = null;
};


?>