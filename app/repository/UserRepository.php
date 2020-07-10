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
        $sql = 'SELECT u.id, u.name, u.email, d.milliliter FROM user u ';
        $sql .= 'LEFT JOIN drink d ON d.id_user = u.id ';

        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = 'SELECT u.id, u.name, u.email, d.milliliter, u.token FROM user u ';
        $sql .= 'LEFT JOIN drink d ON d.id_user = u.id ';
        $sql .= 'WHERE u.id = :id';

        $stmt = $this->connection->prepare($sql);
	    $stmt->bindValue(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($result)) {
            return [null, 204];
        }

        $tokenController = new TokenController();
        if ($tokenController->verify()) {
            unset($result['token']);
            return [$result, 200];
        }

        return [null, 401];
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
			return [null, 200];
        }

        return [null, 500];
    }
}
