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
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (Token::verify($result['token'])) {
            return $result;
        }

        return null;
    }

    public function save(UserModel $user)
    {
        $sql = 'INSERT INTO user (name, email, password) VALUES (:name, :email, :password)';
        $stmt = $this->connection->prepare($sql);
	    $stmt->bindValue(':name', $user->getName());
	    $stmt->bindValue(':email', $user->getEmail());
	    $stmt->bindValue(':password', $user->getPassword());
	    $stmt->execute();

	    if ($stmt->rowCount() > 0) {
            $lastId = $this->connection->lastInsertId();
			return $this->getById($lastId);
        }

        return null;
    }
}
