<?php

class UserRepository
{
    private $connection;

    public function __construct()
    {
        $c = new ConnectionMYSQL();
        $this->connection = $c->getConnection();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM user";
        $result = $this->connection->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = 'SELECT * FROM user WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
	    $stmt->bindValue(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
