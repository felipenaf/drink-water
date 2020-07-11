<?php

class AuthRepository
{
    private $connection;

    public function __construct()
    {
        $c = new ConnectionMYSQL();
        $this->connection = $c->getConnection();
    }

    public function login(UserModel $user)
    {
        $sql = 'SELECT u.id, u.name, u.email, d.milliliter FROM user u ';
        $sql .= 'LEFT JOIN drink d ON d.id_user = u.id ';
        $sql .= 'WHERE u.email = :email AND u.password = :password';

        $stmt = $this->connection->prepare($sql);
	    $stmt->bindValue(":email", $user->getEmail());
	    $stmt->bindValue(":password", $user->getPassword());
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $tokenController = new TokenController();
            $token = $tokenController->generate($result['id'], $result['email']);
            $result['token'] = $token;

            return [$result, 200];
        }

        return ['Usuário não existe ou a senha está inválida', 401];
    }
}
