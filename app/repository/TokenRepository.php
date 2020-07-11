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

    public function save($userId, $token)
    {
        $sql = 'INSERT INTO token (id_user, value) VALUES (:id_user, :value)';
        $stmt = $this->connection->prepare($sql);
	    $stmt->bindValue(':id_user', $userId);
	    $stmt->bindValue(':value', $token);
	    $stmt->execute();

	    if ($stmt->rowCount() > 0) {
			return $token;
        }
    }

    public function deleteByIdUser($id)
    {
        $sql = 'DELETE FROM token WHERE id_user = :id_user';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id_user' => $id]);

        if ($stmt->rowCount() > 0) {
			return true;
        }

        return false;
    }
}
