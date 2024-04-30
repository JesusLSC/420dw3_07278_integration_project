<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project UserDAO.php
 * 
 * @user Marc-Eric Boury (MEbou)
 * @since 2024-04-01
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace DAOs;

use PDO;
use DTOs\UserDTO;
use DTOs\GroupDTO;
use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;
use GivenCode\Services\DBConnectionService;


class UserDAO {
    
    /**
     * TODO: Function documentation
     *
     * @return UserDTO[]
     *
     * @throws RuntimeException
     * @user Marc-Eric Boury
     * @since  2024-04-01
     */
    public function getAll() : array {
        $query = "SELECT * FROM " . UserDTO::TABLE_NAME . ";";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        $users = [];
        
        foreach ($result_set as $result) {
            $users[] = UserDTO::fromDbArray($result);
        }
        return $users;
        
    }
    
    public function getById(int $id) : ?UserDTO {
        $query = "SELECT * FROM " . UserDTO::TABLE_NAME . " WHERE id = :user_id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        $user_array = $statement->fetch(PDO::FETCH_ASSOC);
        return UserDTO::fromDbArray($user_array);
    }
    
    /**
     * TODO: Function documentation
     *
     * @param UserDTO $user
     * @return UserDTO
     *
     * @user Marc-Eric Boury
     * @since  2024-04-01
     */
    public function insert(UserDTO $user) : UserDTO {
        $user->validateForDbCreation();
        $query =
            "INSERT INTO " . UserDTO::TABLE_NAME . " (`username`) VALUES (:username);";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":username", $user->getUsername(), PDO::PARAM_STR);
        $statement->execute();
        $new_id = (int) $connection->lastInsertId();
        return $this->getById($new_id);
    }

    /**
     * TODO: Function documentation
     *
     * @param UserDTO $user
     * @return UserDTO
     *
     * @user Marc-Eric Boury
     * @throws RuntimeException
     * @since  2024-04-01
     */
    public function update(UserDTO $user) : UserDTO {
        $user->validateForDbUpdate();
        $query =
            "UPDATE " . UserDTO::TABLE_NAME .
            " SET `username` = :username WHERE `id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":username", $user->getUsername(), PDO::PARAM_STR);
        $statement->bindValue(":id", $user->getId(), PDO::PARAM_INT);
        $statement->execute();
        return $this->getById($user->getId());
    }

    /**
     * TODO: Function documentation
     *
     * @param UserDTO $user
     * @return void
     *
     * @user Marc-Eric Boury
     * @throws RuntimeException
     * @since  2024-04-01
     */
    public function delete(UserDTO $user) : void {
        $user->validateForDbDelete();
        $query =
            "DELETE FROM " . UserDTO::TABLE_NAME . " WHERE `id` = :id ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":id", $user->getId(), PDO::PARAM_INT);
        $statement->execute();
    }
    
    /**
     * TODO: Function documentation
     *
     * @param UserDTO $user
     * @return GroupDTO[]
     * @throws ValidationException
     * @throws RuntimeException
     *
     * @user Marc-Eric Boury
     * @since  2024-04-01
     */
    public function getGroupsByUser(UserDTO $user) : array {
        if (empty($user->getId())) {
            throw new ValidationException("Cannot get the group records for an user with no set [id] property value.");
        }
        return $this->getGroupsByUserId($user->getId());
        
    }
    
    /**
     * TODO: Function documentation
     *
     * @param int $id
     * @return GroupDTO[]
     * @throws ValidationException
     * @throws RuntimeException
     *
     * @user Marc-Eric Boury
     * @since  2024-04-01
     */
    public function getGroupsByUserId(int $id) : array {
        $query = "SELECT b.* FROM " . UserDTO::TABLE_NAME . " a JOIN " . UserGroupDAO::TABLE_NAME .
            " ab ON a.id = ab.user_id JOIN " . GroupDTO::TABLE_NAME . " b ON ab.group_id = b.id WHERE a.id = :userId ;";
        $connection = DBConnectionService::getConnection();
        $statement = $connection->prepare($query);
        $statement->bindValue(":userId", $id, PDO::PARAM_INT);
        $statement->execute();
        
        $result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        $groups_array = [];
        foreach ($result_set as $group_record_array) {
            $groups_array[] = GroupDTO::fromDbArray($group_record_array);
        }
        return $groups_array;
        
    }
}