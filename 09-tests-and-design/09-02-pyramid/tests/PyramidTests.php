<?php

declare(strict_types=1);

class User
{
}

class DatabaseAccessor
{
    public function executeSql(string $sql): User
    {
        return new User();
    }
}

class UserRepository
{
    private DatabaseAccessor $accessor;

    public function __construct(DatabaseAccessor $accessor)
    {
        $this->accessor = $accessor;
    }

    public function findUserById(string $id): User
    {
        return $this->accessor->executeSql('...');
    }
}

class UserController
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function user(string $id): User
    {
        return $this->user($id);
    }
}
