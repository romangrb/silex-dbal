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
    
    protected $status;
    
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
                            
        $this->status = $this->statusHandler();
                            
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
    
    public function getPerson( $personAttrArr ) {
        $this->wr->addInfo(json_encode($personAttrArr));
        return;
        $sql = "        
                SELECT
                    A.value as 'first name',  
                    B.value as 'last name',
                    C.value as 'middle name',
                    D.dir as 'persons folder',
                    E.profile_path as 'path'
                FROM
                    {$this->tables['f']} A,
                    {$this->tables['l']} B,
                    {$this->tables['m']} C, 
                    {$this->tables['p']} D,
                    {$this->tables['p']} E
                
                WHERE
                    A.id = $f
                AND
                    B.id = $l
                AND
                    D.person = A.id
         
                LIMIT 1
              ";
        
        return $this->conn->fetchAll($sql); 
    }
    
    public function getPersonPage( $offset, $limit ) {
        
        $sql = "        
                SELECT
                    A.value as 'first name',  
                    B.value as 'last name',
                    C.value as 'middle name',
                    D.dir as 'persons folder',
                    E.profile_path as 'path'
                FROM
                    {$this->tables['f']} A,
                    {$this->tables['l']} B,
                    {$this->tables['m']} C, 
                    {$this->tables['p']} D,
                    {$this->tables['p']} E
                
                WHERE
                    A.id = B.id
                AND
                    B.id = C.id
                AND
                    D.person = C.id
         
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
    
    public function addProfileFiles($id, $path, $dir) {
        
        $this->status->is_failed = false;
        
        $sql = "UPDATE {$this->tables['p']} SET profile_path =?, dir=? WHERE person =?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, (string) $path);
        $stmt->bindValue(2, (string) $dir);
        $stmt->bindValue(3, (int) $id);
        
        try{
            $this->conn->beginTransaction();
            if (! $stmt->execute()) throw new Exception("Not updated persin id , id : $id, dir : $dir");
        } catch (\Exception $e) {
            $info = $this->exHandler($e, 1);
            $this->conn->rollBack();
            $this->status->txt = $info;
            $this->status->is_failed = true;
        }
        
        $this->conn->commit();
        
        return $this->status;
        
    }
    
    private function exHandler($e, $num){
        $info = array('exeption' . $num => $e);
        $this->wr->addError('exeption', $info);
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
            $this->status->id = $id;
            
            try {
                foreach ($pers_attr as $key => $value ) $this->prepareAddPersonAttr($key, $id, $value);
            
            } catch (\Exception $e) {
                $info = $this->exHandler($e, 1);
                $this->conn->rollBack();
                $this->conn->beginTransaction(); 
                $this->status->txt = $info;
                $this->status->is_failed = true;
            }
            
            $this->conn->commit();
        
        } catch (\Exception $e) {
            $info = $this->exHandler($e, 2);
            $this->conn->rollBack();
            $this->status->txt = $info;
            $this->status->is_failed = true;
        }
        
        return $this->status;
       
    }
    
    private function statusHandler($is_success = true, $txt = '', $dir = null){
        $obj = new \stdClass();
        $obj->txt = $txt;
        $obj->id = $id;
        $obj->is_failed = false;
        return $obj; 
    }
    

} 
