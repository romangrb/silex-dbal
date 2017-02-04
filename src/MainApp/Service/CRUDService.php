<?php
namespace MainApp\Service;

use MainApp\Repository\CRUDRepository;
use Doctrine\DBAL\Connection;
use MainApp\Entity\Message;

class CRUDService
{
    protected $connection;

    protected $messageRepository;
    
    protected $table;
    
    public function __construct(Connection $connection) {
        $this->connection = $connection;
        $this->messageRepository = $messageRepository;
        $this->table = 'test';
    }

    public function fetchAll() {
        // return $this->messageRepository->fetchAll();
        return 123;
    }
    
     public function fetch($id) {
        return $this->messageRepository->fetch($id);
    }
    
    public function add(Message $crud) {
            $sql = "INSERT INTO $this->table (name)VALUES(?)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(1, $crud->getText());
            $stmt->bindValue(2, $crud->getDate());
            $stmt->execute();
        }
        
      public function delete($id) {
            $sql = "DELETE FROM $this->table WHERE id=?";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
      }

       public function update(Message $crud) {
            $sql = "UPDATE $this->table SET name =? WHERE id=?";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(1, $crud->getText());
            $stmt->bindValue(2, $crud->getDate());
            $stmt->bindValue(3, $crud->getId());
            $stmt->execute();
        }
} 
