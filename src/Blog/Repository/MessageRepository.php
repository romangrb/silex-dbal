<?php

namespace Blog\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Blog\Entity\Message;

class MessageRepository
{

    private $connection;

    private static $MAPPING = array(
        'id' => 'setId',
        'text' => 'setText',
        'created_at' => 'setDate',
    );

    /**
     * MessageManager constructor.
     *
     * @param $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function fetchAll()
    {
        // $sql = "SELECT * FROM messages";
        $sql = "SELECT * FROM test";
        return $this->hydrateAllData($this->connection->fetchAll($sql));
    }

    public function fetch($id)
    {
        $sql = "SELECT * FROM messages WHERE id =". $id;
        return $this->hydrateAllData($this->connection->fetchAll($sql))[0];
    }
    /**
     * Le nom de la méthode est tiré de celui présent dans AbstractHydrator de Doctrine ORM.
     * Le prototype n'est cependant pas respecté.
     *
     * @param $rows
     * @return ArrayCollection
     */
    protected function hydrateAllData(array $rows)
    {
        $messages = new ArrayCollection();

        foreach($rows as $row) {
            $messages->add($this->hydrateRowData($row));
        }

        return $messages;
    }

    /**
     * Le nom de la méthode est tiré de celui présent dans AbstractHydrator de Doctrine ORM.
     * Le prototype n'est cependant pas respecté.
     *
     * @param $row
     * @return Message
     */
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
