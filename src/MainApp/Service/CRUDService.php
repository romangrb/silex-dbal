<?php
namespace MainApp\Service;

use MainApp\Repository\CRUDRepository;
use Doctrine\DBAL\connection;
use MainApp\Entity\Message;

class CRUDService
{
    protected $con;

    protected $crudRepository;
    
    protected $table;
    
    public function __construct(connection $connection, crudRepository $crudRepository) {
        $this->con = $connection;
        $this->crudRepository = $crudRepository;
        $this->table = 'test';
    }

    public function fetchAll() {
        return $this->crudRepository->fetchAll();
    }
    
    public function getPage( $num ) {
        // $sql = "SELECT * FROM $this->table LIMIT 20";
        // return $this->con->fetchAssoc($sql); 
        return 20;
    }
    
    
    public function fetch($id) {
        return $this->crudRepository->fetch($id);
    }
    
    public function add(Message $crud) {
            $sql = "INSERT INTO $this->table (name)VALUES(?)";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(1, $crud->getText());
            $stmt->bindValue(2, $crud->getDate());
            $stmt->execute();
        }
        
      public function delete($id) {
            $sql = "DELETE FROM $this->table WHERE id=?";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
      }

       public function update(Message $crud) {
            $sql = "UPDATE $this->table SET name =? WHERE id=?";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(1, $crud->getText());
            $stmt->bindValue(2, $crud->getDate());
            $stmt->bindValue(3, $crud->getId());
            $stmt->execute();
        }
} 
