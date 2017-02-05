<?php
namespace MainApp\Service;

use MainApp\Repository\CRUDRepository;
use Doctrine\DBAL\connection;
use MainApp\Entity\Message;

class CRUDService
{
    protected $con;

    protected $crudRepository;
    
    protected $tables;
    
    public function __construct(connection $connection, crudRepository $crudRepository) {
        $this->con = $connection;
        $this->crudRepository = $crudRepository;
        $this->tables = array('first' =>'first_name',
                              'second'=>'last_name',
                              'third' =>'full_name'
                            );
    }

    public function fetchAll() {
        $sql = "SELECT * FROM $this->tables['first']";
        return $this->con->fetchAssoc($sql);
    }
    
    public function getPage( $offset, $limit ) {
        $sql = "SELECT * FROM {$this->tables['first']} LIMIT $offset, $limit";
        return $this->con->fetchAll($sql); 
    }
    
    public function addFullName( $offset, $limit ) {
        $sql = "INSERT INTO $this->tables['first'] (name)VALUES(?)";
        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(1, $crud->getText());
        $stmt->bindValue(2, $crud->getDate());
        $stmt->execute();
    }
    
    // public function fetch($id) {
    //     return $this->crudRepository->fetch($id);
    // }
    
    // public function add(Message $crud) {
    //         $sql = "INSERT INTO $this->tables['first'] (name)VALUES(?)";
    //         $stmt = $this->con->prepare($sql);
    //         $stmt->bindValue(1, $crud->getText());
    //         $stmt->bindValue(2, $crud->getDate());
    //         $stmt->execute();
    //     }
        
    //   public function delete($id) {
    //         $sql = "DELETE FROM $this->tables['first'] WHERE id=?";
    //         $stmt = $this->con->prepare($sql);
    //         $stmt->bindValue(1, $id);
    //         $stmt->execute();
    //   }

    //   public function update(Message $crud) {
    //         $sql = "UPDATE $this->tables['first'] SET name =? WHERE id=?";
    //         $stmt = $this->con->prepare($sql);
    //         $stmt->bindValue(1, $crud->getText());
    //         $stmt->bindValue(2, $crud->getDate());
    //         $stmt->bindValue(3, $crud->getId());
    //         $stmt->execute();
    //     }
} 
