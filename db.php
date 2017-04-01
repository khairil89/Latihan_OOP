<?php

class Database
{
	public $pdo;

	public function __construct()
	{
		$host = 'localhost';
		$db   = 'latihan';
		$user = 'root';
		$pass = '';

		try
		{
			$this->pdo = new PDO('mysql:host='.$host.';dbname='.$db,$user, $pass, [PDO::ATTR_PERSISTENT => true]);
		}
		catch(PDOException $e)
		{
			throw new Exception('Ga bisa connect ke database');
		}
	}

	public function selectAll($data, $table)
	{
		$sql = "SELECT $data FROM $table";
		$selectAll = $this->pdo->prepare($sql);
		$selectAll->execute();
		$fetching = $selectAll->fetchAll(PDO::FETCH_OBJ);
		return $fetching;
	}

	public function selectOne($data, $table, $where = NULL, $additional = NULL)
	{
		$query = "SELECT $data FROM $table";
		if($where != NULL)
		{
			$sql = $query .= "WHERE $where";
		}
		$selectAll = $this->pdo->prepare($sql);
		$selectAll->execute();
		$fetching = $selectAll->fetchAll(PDO::FETCH_OBJ);
		return $fetching;
	}	
}