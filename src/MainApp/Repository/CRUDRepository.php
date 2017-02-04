<?php

namespace MainApp\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use MainApp\Entity\Message;

class CRUDRepository
{
    
    private $connection;
    
    private $table;
    
    private static $MAPPING = array(
        'id' => 'setId',
        'text' => 'setText',
        'created_at' => 'setDate',
    );

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->table = 'test';
    }

    public function fetchAll()
    {
        return 'zz';
        // $sql = "SELECT * FROM $this->table";
        // return $this->hydrateAllData($this->connection->fetchAll($sql));
    }

    public function fetch($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id =". $id;
        return $this->hydrateAllData($this->connection->fetchAll($sql))[0];
    }
    
    protected function hydrateAllData(array $rows)
    {
        $messages = new ArrayCollection();

        foreach($rows as $row) {
            $messages->add($this->hydrateRowData($row));
        }

        return $messages;
    }

    protected function hydrateRowData(array $row)
    {
        $message = new Message();

        foreach(self::$MAPPING as $fieldName => $method) {
            if(array_key_exists($fieldName, $row) && method_exists($message, $method)) {
                call_user_func(array($message, $method), $row[$fieldName]);
            }
        }

        return $message;
    }
} 
