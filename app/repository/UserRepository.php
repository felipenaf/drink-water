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

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            return [null, 204];
        }

        $tokenController = new TokenController();
        if ($tokenController->verify()) {
            return [$result, 200];
        }

        return [null, 401];
    }

    public function getById($id)
    {
        $sql = 'SELECT u.id, u.name, u.email, d.milliliter FROM user u ';
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
            return [$result, 200];
        }

        return [null, 401];
    }

    public function save(UserModel $user)
    {
        if ($this->getByEmail($user->getEmail())) {
            return ["Usuário já existe", 409];
        }

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

    private function getByEmail($email)
    {
        $sql = 'SELECT * FROM user WHERE email = :email';

        $stmt = $this->connection->prepare($sql);
	    $stmt->bindValue(":email", $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(UserModel $user)
    {
        $tokenController = new TokenController();
        $token = $tokenController->verify();

        if ($token['id_user'] != $user->getId()) {
            return [null, 401];
        }

        $sql = 'UPDATE user SET name = :name, email = :email, password = :password WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'id' => $user->getId()
        ]);

        if ($stmt->rowCount()) {
			return [null, 200];
        }
    }

    public function getByIdForUpdate($id)
    {
        $sql = 'SELECT * FROM user WHERE id = :id';

        $stmt = $this->connection->prepare($sql);
	    $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $tokenController = new TokenController();
        $token = $tokenController->verify();

        if ($token['id_user'] != $id) {
            return [null, 401];
        }

        $tokenController->deleteByIdUser($id);

        $sql = 'DELETE FROM user WHERE id = :id';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount()) {
			return [null, 200];
        }
    }

    public function drink($id, $drinkMl)
    {
        $tokenController = new TokenController();
        $token = $tokenController->verify();

        if ($token['id_user'] != $id) {
            return [null, 401];
        }

        $sql = 'INSERT INTO drink (id_user, milliliter) VALUES (:id_user, :milliliter)';
        $stmt = $this->connection->prepare($sql);
	    $stmt->bindValue(':id_user', $id);
	    $stmt->bindValue(':milliliter', $drinkMl);
	    $stmt->execute();

	    if ($stmt->rowCount() > 0) {
            $drinks = $this->countDrink($id);
            $result = $drinks[0];
            $result['drink_counter'] = count($drinks);
			return [$result, 200];
        }

        return [null, 500];
    }

    private function countDrink($id)
    {
        $sql = 'SELECT u.id, u.name, u.email FROM user u left join drink d on d.id_user = u.id WHERE id_user = :id_user';
        $stmt = $this->connection->prepare($sql);
	    $stmt->bindValue(":id_user", $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
