<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Contracts\DatabaseServiceInterface;

class DevDatabaseService implements DatabaseServiceInterface
{
    protected $connection;
  
    public function __construct()
    {
        $this->connection = DB::connection('sqlsrv_dev');
    }

    public function findUserByEmail(string $email)
    {
        return $this->connection->table('users')->where('email', $email)->first();
    }

    public function createUser(array $userData)
    {
        return $this->connection->table('users')->insert($userData);
    }

    public function storeUser(array $userData)
    {
        return $this->connection->table('users')->insert($userData);
    }
    // public function getUsers(){
    //     return $this->connection->table('Usuario')
    // }

}