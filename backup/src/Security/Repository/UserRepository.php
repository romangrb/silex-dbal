<?php

namespace Security\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Security\Entity\User;

class UserRepository
{

    private $connection;

    private static $MAPPING = array(
        'id' => 'setId',
        'firstname' => 'setFirstname',
        'lastname' => 'setLastname',
        'email' => 'setEmail',
        'password' => 'setPassword',
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
        $sql = "SELECT * FROM users";
        return $this->hydrateAllData($this->connection->fetchAll($sql));
    }

    public function fetchOneByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email =". $email;
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
        $users = new ArrayCollection();

        foreach($rows as $row) {
            $users->add($this->hydrateRowData($row));
        }

        return $users;
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
        $users = new User();

        foreach(self::$MAPPING as $fieldName => $method) {
            if(array_key_exists($fieldName, $row) && method_exists($users, $method)) {
                call_user_func(array($users, $method), $row[$fieldName]);
            }
        }

        return $users;
    }
} 
 
