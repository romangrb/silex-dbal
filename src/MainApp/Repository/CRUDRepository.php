<?php

namespace MainApp\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use MainApp\Entity\Message;

class CRUDRepository
{
    
    private $con;
    
    private $table;
    
    public function __construct(Connection $connection)
    {
        $this->con = $connection;
        $this->table = 'test';
    }

    public function fetchAll()
    {
        $sql = "SELECT * FROM $this->table";
        return $this->con->fetchAssoc($sql);
    }
    
    public function getTop ()
    {
       $sql = "SELECT * FROM $this->table LIMIT 20";
       return $this->con->fetchAssoc($sql); 
    }
    
    public function fetch($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id =". $id;
        return $this->hydrateAllData($this->connection->fetchAll($sql))[0];
    }
    
} 
