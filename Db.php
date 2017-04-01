<?php

Class Database
{
	public $connection;

	function __construct($DB_driver, $DB_host, $DB_name, $DB_user, $DB_pass)
	{
		try
		{
			$this->connection = new PDO("{$DB_driver}:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		catch(PDOException $e) 
		{
			echo "Koneksi Gagal : <br>" . $e->getMessage();
		}
	}

	public function insertData($table, $rows = NULL)
	{
		$sql = "INSERT INTO $table";
		$row = NULL;
		$value = NULL;
		//looping setiap nilai array
		foreach($rows as $key => $nilai)
		{
			$row .= "," . $key;
			$value .= ",'".$nilai."'";
		}
		$sql .= "(".substr($row,1).")"; //nama field yang akan diinsert
		$sql .= "(".substr($value,1).")"; //nama value yang akan diinsert
		//echo $sql;
		$query = $this->connection->prepare($sql);
		return $query;
	}

	public function fetchData($table, $field = '*', $where = NULL, $addition = NULL) 
	{
		if(is_array($field)) 
		{
			$fields = implode(",", $field);
		}
		elseif(count($field) == 1)
		{
			$fields = $field;
		}
		else 
		{
			$fields = "*";
		}
		$sql = "SELECT " .$fields. " FROM " .$table;
		if($where != NULL) 
		{
			$sql .= " WHERE $where";
		}
		if($addition != NULL)
		{
			$sql .= $addition;
		}
		echo $sql;
		$query = $this->connection->prepare($sql);
		$query->execute();
		$fetch = $query->fetchAll(PDO::FETCH_OBJ);
		return $fetch;
	}

	public function fetchJoin($table = NULL, $field = "*", $onField = NULL, $where = NULL, $addition = NULL) 
	{
		if(is_array($table)) 
		{
			$tables = implode( " JOIN ", $table);
		}
		else 
		{
			$tables = $table;
		}

		if(is_array($field)) 
		{
			$fields = implode(",", $field);
		}
		elseif(count($field) == 1)
		{
			$fields = $field;
		}
		else 
		{
			$fields = "*";
		}

		$sql = "SELECT " .$fields. " FROM " . $tables;
		if($where != NULL) 
		{
			$sql .= " WHERE $where";
		}
		if($addition != NULL)
		{
			$sql .= $addition;
		}
		//echo $sql;
		$query = $this->connection->prepare($sql);
		$query->execute();
		$fetch = $query->fetchAll(PDO::FETCH_OBJ);
		return $fetch;
	}

	public function updateData($table, $field = NULL, $where)
	{
		$sql      = "UPDATE $table SET";
		$setAlias = NULL;
		foreach ($field as $key => $values) 
		{
			$setAlias .= ", " .$key." = '".$values."'";
		}
		$sql .= substr($setAlias,1) ." WHERE $where";
		// echo $sql; 
		$query = $this->connection->prepare($sql);
		return $query();
	}

	public function deleteData($table, $where)
	{
		$sql = "DELETE FROM $table WHERE $where";
		//echo $sql;
		$query = $this->connection->prepare($sql);
		return $query();
	}
}

//Batas instasiasi
if($_SERVER['HTTP_HOST'] == 'localhost')
	{
		$class = New Database('mysql', 'localhost', 'lapkeu', 'root', '');
	}
else 
	{
		$class = New Database('mysql', 'domain[dot]com', 'lapkeu', 'dev_php', 'blablabla');
	}

// $tablearray = array('lk_jurnal b','lk_assigncode a','lk_signcode c');
// $dataArray = array('b.nom1','a.acid','c.scid');
// $foreignArray = 'a.acid = b.acid';
// $class->fetchJoin($tablearray,$dataArray,$foreignArray);

?>