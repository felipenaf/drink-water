<?php

class ConnectionMYSQL implements ConnectionDB
{
	private $connection;

	public function __construct() {
		$this->getConnection();
	}

	public function closeAll() {
		$this->connection = NULL;
	}

	public function getConnection() {
		$dbInfo = parse_ini_file("config/database.ini");

		$this->connection = $this->PDOConnection($dbInfo);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->connection->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);

		return $this->connection;
	}

	private function PDOConnection($dbInfo) {
		$db = new PDO("mysql:host={$dbInfo['hostname']};dbname={$dbInfo['database']}", $dbInfo['username'], $dbInfo['password']);
		$db->exec("set names utf8");
		return $db;
	}
}
