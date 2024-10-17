<?php
namespace App\Contracts;

interface DatabaseServiceInterface
{
    public function findUserByEmail(string $email);
    public function createUser(array $userData);
    public function storeUser(array $userData);
    // public function getUsers();

}