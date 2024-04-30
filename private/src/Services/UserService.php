<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project UserService.php
 * 
 * @user Marc-Eric Boury (MEbou)
 * @since 2024-04-03
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Services;

use Exception;
use DAOs\UserDAO;
use DTOs\UserDTO;
use GivenCode\Abstracts\IService;
use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;
use GivenCode\Services\DBConnectionService;

/**
 * TODO: Class documentation
 *
 * @user Marc-Eric Boury
 * @since  2024-04-03
 */
class UserService implements IService {
    
    private UserDAO $dao;
    
    public function __construct() {
        $this->dao = new UserDAO();
    }
    
    /**
     * TODO: Function documentation
     *
     * @return UserDTO[]
     * @throws RuntimeException
     *
     * @user Marc-Eric Boury
     * @since  2024-04-06
     */
    public function getAllUsers() : array {
        return $this->dao->getAll();
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserById(int $id) : ?UserDTO {
        $user = $this->dao->getById($id);
        $user?->loadGroups();
        return $user;
    }
    
    public function createUser(string $firstName, string $lastName) : UserDTO {
        try {
            $user = UserDTO::fromValues($firstName, $lastName);
            return $this->dao->insert($user);
            
        } catch (Exception $excep) {
            throw new RuntimeException("Failure to create user [$firstName, $lastName].", $excep->getCode(), $excep);
        }
    }
    
    public function updateUser(int $id, string $firstName, string $lastName) : UserDTO {
        try {
            $connection = DBConnectionService::getConnection();
            $connection->beginTransaction();
            
            try {
                $user = $this->dao->getById($id);
                if (is_null($user)) {
                    throw new Exception("User id# [$id] not found in the database.");
                }
                $user->setUsername($firstName);
                $result = $this->dao->update($user);
                $connection->commit();
                return $result;
                
            } catch (Exception $inner_excep) {
                $connection->rollBack();
                throw $inner_excep;
            }
            
        } catch (Exception $excep) {
            throw new RuntimeException("Failure to update user id# [$id].", $excep->getCode(), $excep);
        }
    }
    
    public function deleteUserById(int $id) : void {
        try {
            
            $connection = DBConnectionService::getConnection();
            $connection->beginTransaction();
            
            try {
                $user = $this->dao->getById($id);
                if (is_null($user)) {
                    throw new Exception("User id# [$id] not found in the database.");
                }
                $this->dao->delete($user);
                $connection->commit();
                
            } catch (Exception $inner_excep) {
                $connection->rollBack();
                throw $inner_excep;
            }
            
        } catch (Exception $excep) {
            throw new RuntimeException("Failure to delete user id# [$id].", $excep->getCode(), $excep);
        }
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserGroups(UserDTO $user) : array {
        return $this->getUserGroupsByUserId($user->getId());
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function getUserGroupsByUserId(int $id) : array {
        return $this->dao->getGroupsByUserId($id);
    }
    
}