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
        $this->wr = new Logger('crud_logger');
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
       
        $sql = "        
                SELECT
                    A.value as 'first name',  
                    B.value as 'last name',
                    C.value as 'middle name'
                FROM
                    {$this->tables['f']} A,
                    {$this->tables['l']} B,
                    {$this->tables['m']} C    
                
                WHERE
                    A.id = B.id
                AND
                    B.id = C.id
                ";
                
        return $this->conn->fetchAll($sql);
    }
    
    public function getPersonPage( $offset, $limit ) {
        $sql = "        
                SELECT
                    A.value as 'first name',  
                    B.value as 'last name',
                    C.value as 'middle name'
                FROM
                    {$this->tables['f']} A,
                    {$this->tables['l']} B,
                    {$this->tables['m']} C    
                
                WHERE
                    A.id = B.id
                AND
                    B.id = C.id
                LIMIT $offset, $limit
              ";
        
        return $this->conn->fetchAll($sql); 
    }
    
    private function prepareAddPersonAttr($table_n, $id, $name) {
            
        $sql = "INSERT INTO {$this->tables[$table_n]} VALUES(?, ? )";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, (int) $id);
        $stmt->bindValue(2, (string) $name);
        $stmt->execute();
        
    }
    
    private function exHandler($e, $num){
        $info = array('exeption' . $num => $e);
        $this->wr->addInfo('exeption', $info);
        return $info;
    }
    
    public function addPerson($pers_attr) {
    
        $sql = "INSERT INTO {$this->tables['p']} (is_existed) VALUES(?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, 1);
      
        try{
            $this->conn->beginTransaction();
            $stmt->execute();
            $id = $this->conn->lastInsertId();
           
            try {
                foreach ($pers_attr as $key => $value ) $this->prepareAddPersonAttr($key, $id, $value);
            
            } catch (\Exception $e) {
                $info = $this->exHandler($e, 1);
                $this->conn->rollBack();
                $this->conn->beginTransaction(); 
            }
            
            $this->conn->commit();
        
        } catch (\Exception $e) {
            $info = $this->exHandler($e, 2);
            $this->conn->rollBack();
        }
        
        return (is_null($info))? array('added', array('id'=>$id)) : array('error'=>$info);
       
    }
    

} 
