<?php

class TokenRepository
{
    private $connection;

    public function __construct()
    {
        $c = new ConnectionMYSQL();
        $this->connection = $c->getConnection();
    }

    public function getByValue($value)
    {
        $sql = 'SELECT * FROM token WHERE value = :value';

        $stmt = $this->connection->prepare($sql);
	    $stmt->bindValue(":value", $value);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($token)
    {
        $sql = 'INSERT INTO token (value) VALUES (:value)';
        $stmt = $this->connection->prepare($sql);
	    $stmt->bindValue(':value', $token);
	    $stmt->execute();

	    if ($stmt->rowCount() > 0) {
			return $token;
        }
    }
}
