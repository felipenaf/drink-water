<?php

class UserController
{
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function getAll()
    {
        return $this->userRepository->getAll();
    }

    public function getById(int $id)
    {
        return $this->userRepository->getById($id);
    }

    public function save(UserModel $user)
    {
        return $this->userRepository->save($user);
    }

    public function update(UserModel $user)
    {
        return $this->userRepository->update($user);
    }

    public function getByIdForUpdate(int $id)
    {
        return $this->userRepository->getByIdForUpdate($id);
    }

    public function delete(int $id)
    {
        return $this->userRepository->delete($id);
    }
}
