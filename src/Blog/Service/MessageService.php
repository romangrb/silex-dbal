<?php
namespace Blog\Service;

use Blog\Repository\MessageRepository;
use Doctrine\DBAL\Connection;
use Blog\Entity\Message;

class MessageService
{
    protected $connection;

    protected $messageRepository;

    public function __construct(Connection $connection, MessageRepository $messageRepository) {
        $this->connection = $connection;
        $this->messageRepository = $messageRepository;
    }

    public function fetchAll() {
        return $this->messageRepository->fetchAll();
    }
    
     public function fetch($id) {
        return $this->messageRepository->fetch($id);
    }
    
    public function add(Message $message) {
            $sql = "INSERT INTO messages(text,created_at)VALUES(?,?)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(1, $message->getText());
            $stmt->bindValue(2, $message->getDate());
            $stmt->execute();
        }
        
      public function delete($id) {
            $sql = "DELETE FROM messages WHERE id=?";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(1, $id);
            $stmt->execute();
      }

       public function update(Message $message) {
            $sql = "UPDATE messages SET text =?,created_at=? WHERE id=?";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(1, $message->getText());
            $stmt->bindValue(2, $message->getDate());
            $stmt->bindValue(3, $message->getId());
            $stmt->execute();
        }
} 
