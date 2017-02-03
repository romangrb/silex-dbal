<?php
namespace Security\Service;

use Security\Repository\UserRepository;
use Doctrine\DBAL\Connection;
use Security\Entity\User;

class UserService
{
    protected $connection;

    protected $UserRepository;

    public function __construct(Connection $connection, UserRepository $userRepository) {
        $this->connection = $connection;
        $this->userRepository = $userRepository;
    }

    public function fetchAll() {
        return $this->userRepository->fetchAll();
    }
    
     public function fetchOneByEmail($id) {
        return $this->userRepository->fetch($id);
    }
    
    public function add(User $user) {
            $sql = "INSERT INTO users(text,created_at)VALUES(?,?)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(1, $user->getText());
            $stmt->bindValue(2, $user->getDate());
            $stmt->execute();
        }
        
      public function delete($id) {
            $sql = "DELETE FROM users WHERE id=?";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
      }

       public function update(User $user) {
            $sql = "UPDATE users SET text =?,created_at=? WHERE id=?";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(1, $user->getText());
            $stmt->bindValue(2, $user->getDate());
            $stmt->bindValue(3, $user->getId());
            $stmt->execute();
        }
} 
 
