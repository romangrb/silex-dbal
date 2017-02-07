<?php
namespace MainApp\Service;

use Doctrine\DBAL\connection;
use MainApp\Entity\Message;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class CRUDService
{
    protected $conn;

    protected $crudRepository;
    
    protected $tables;
    
    protected $wr;
    
    protected function init_logger(){
        
        $stream = new StreamHandler(__DIR__.'/crud_service.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();
        $this->wr = new Logger('my_logger');
        $this->wr->pushHandler($stream);
        $this->wr->pushHandler($firephp);
        
    }
    
    public function __construct(connection $connection) {
        $this->conn = $connection;
        
        $this->init_logger();
        
        $this->tables = array('f' =>'first_name',
                              'l' =>'last_name',
                              'm' =>'middle_name',
                              'p' =>'person'
                            );
                            
    }

    public function fetchAll() {
        $sql = "SELECT * FROM $this->tables['f']";
        return $this->conn->fetchAssoc($sql);
    }
    
    public function getPage( $offset, $limit, $mlog ) {
        $sql = "SELECT * FROM {$this->tables['f']} LIMIT $offset, $limit";
        return $this->conn->fetchAll($sql); 
    }
    
    public function addPerson($f_n, $l_n, $m_n) {
        $this->wr->addInfo('p', array($f_n, $l_n, $m_n));
        
        $sql = "INSERT INTO {$this->tables['f']} (value) VALUES(?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, 'test');
        $this->conn->beginTransaction();
        
        $sqld = "INSERT INTO {$this->tables['second']} (value) VALUES(?)";
        $stmtd = $this->conn->prepare($sqld);
        $stmtd->bindValue(1, null);
        
        try{
            $stmt->execute();
            $stmtd->execute();
            $this->conn->commit();
        } catch (\Exception $e) {
            $this->conn->rollBack();
            // $this->wr->addInfo('exeption', array('exeption' => $e));
            throw $e;
        } 
        $this->wr->addInfo('info-log', array('name' => $name));
       
    }
    
    //     $conn->beginTransaction();
    // try{
    //     // do stuff
    //     $conn->commit();
    // } catch (\Exception $e) {
    //     $conn->rollBack();
    //     throw $e;
    // }
    
    
//     $em->getConnection()->beginTransaction(); // suspend auto-commit
// try {
//     //... do some work
//     $user = new User;
//     $user->setName('George');
//     $em->persist($user);
//     $em->flush();
//     $em->getConnection()->commit();
// } catch (Exception $e) {
//     $em->getConnection()->rollBack();
//     throw $e;
// }
    
    
    
    public function addFullName( $offset, $limit ) {
        $sql = "INSERT INTO $this->tables['first'] (name)VALUES(?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $crud->getText());
        $stmt->bindValue(2, $crud->getDate());
        $stmt->execute();
    }
    
    // public function fetch($id) {
    //     return $this->crudRepository->fetch($id);
    // }
    
    // public function add(Message $crud) {
    //         $sql = "INSERT INTO $this->tables['first'] (name)VALUES(?)";
    //         $stmt = $this->conn->prepare($sql);
    //         $stmt->bindValue(1, $crud->getText());
    //         $stmt->bindValue(2, $crud->getDate());
    //         $stmt->execute();
    //     }
        
    //   public function delete($id) {
    //         $sql = "DELETE FROM $this->tables['first'] WHERE id=?";
    //         $stmt = $this->conn->prepare($sql);
    //         $stmt->bindValue(1, $id);
    //         $stmt->execute();
    //   }

    //   public function update(Message $crud) {
    //         $sql = "UPDATE $this->tables['first'] SET name =? WHERE id=?";
    //         $stmt = $this->conn->prepare($sql);
    //         $stmt->bindValue(1, $crud->getText());
    //         $stmt->bindValue(2, $crud->getDate());
    //         $stmt->bindValue(3, $crud->getId());
    //         $stmt->execute();
    //     }
} 
