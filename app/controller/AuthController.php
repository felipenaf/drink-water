<?php

class AuthController
{
    private $authRepository;

    public function __construct()
    {
        $this->authRepository = new AuthRepository();
    }

    public function login(UserModel $user)
    {
        return $this->authRepository->login($user);
    }
}
