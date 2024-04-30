<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project UserDTO.php
 * 
 * @user Marc-Eric Boury (MEbou)
 * @since 2024-03-21
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace DTOs;

use DateTime;
use Exception;
use DAOs\UserDAO;
use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;

/**
 * TODO: Class documentation
 *
 * @user Marc-Eric Boury
 * @since  2024-03-21
 */
class UserDTO {
    
    /**
     * The database table name for this entity type.
     * @const
     */
    public const TABLE_NAME = "users";
    public const USERNAME_MAX_LENGTH = 255;
    public const PASSWORD_MAX_LENGTH = 255;
    public const EMAIL_MAX_LENGTH = 255;
    
    private int $user_id;
    private string $username;
    private string $password_hash;
    private string $email;
    private ?DateTime $created_at = null;
    private ?DateTime $modified_at = null;
    
    /**
     * TODO: Property documentation
     *
     * @var GroupDTO[]
     */
    private array $groups = [];
    
    
    public function __construct() {}
    
    /**
     * TODO: Function documentation
     *
     * @param string $username
     * @param string $password_hash
     * @return UserDTO
     *
     * @user Marc-Eric Boury
     * @since  2024-04-01
     */
    public static function fromValues(string $username, string $password_hash) : UserDTO {
        $instance = new UserDTO();
        $instance->setUsername($username);
        return $instance;
    }

    /**
     * TODO: Function documentation
     *
     * @param array $dbArray
     * @return UserDTO
     *
     * @user Marc-Eric Boury
     * @throws ValidationException
     * @since  2024-04-01
     */
    public static function fromDbArray(array $dbArray) : UserDTO {
        self::validateDbArray($dbArray);
        $instance = new UserDTO();
        $instance->setId((int) $dbArray["user_id"]);
        $instance->setUsername($dbArray["username"]);
        $instance->setPassword($dbArray["password_hash"]);
        $instance->setEmail($dbArray["email"]);
        $instance->setDateCreated(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["created_at"]));
        if (!empty($dbArray["modified_at"])) {
            $instance->setDateLastModified(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["modified_at"]));
        }
        return $instance;
    }
    
    // <editor-fold defaultstate="collapsed" desc="VALIDATION METHODS">

    /**
     * @throws ValidationException
     */
    private static function validateDbArray(array $dbArray) : void {
        if (empty($dbArray["user_id"])) {
            throw new ValidationException("Record array does not contain an [user_id] field. Check column names.");
        }
        if (!is_numeric($dbArray["user_id"])) {
            throw new ValidationException("Record array [user_id] field is not numeric. Check column types.");
        }
        if (empty($dbArray["username"])) {
            throw new ValidationException("Record array does not contain an [username] field. Check column names.");
        }
        if (empty($dbArray["password_hash"])) {
            throw new ValidationException("Record array does not contain an [password_hash] field. Check column names.");
        }
        if (empty($dbArray["email"])) {
            throw new ValidationException("Record array does not contain an [email] field. Check column names.");
        }
        if (empty($dbArray["created_at"])) {
            throw new ValidationException("Record array does not contain an [created_at] field. Check column names.");
        }
        if (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["created_at"]) === false) {
            throw new ValidationException("Failed to parse [created_at] field as DateTime. Check column types.");
        }
        if (!empty($dbArray["modified_at"]) &&
            (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["modified_at"]) === false)
        ) {
            throw new ValidationException("Failed to parse [modified_at] field as DateTime. Check column types.");
        }
    }

    /**
     * @throws ValidationException
     */
    public function validateForDbCreation(bool $optThrowExceptions = true) : bool {
        // ID must not be set
        if (!empty($this->user_id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: ID value already set.");
            }
            return false;
        }
        // username is required
        if (empty($this->username)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: username value not set.");
            }
            return false;
        }
        // password_hash is required
        if (empty($this->password_hash)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: password_hash value not set.");
            }
            return false;
        }
        if (empty($this->email)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: email value not set.");
            }
            return false;
        }
        if (!is_null($this->created_at)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: created_at value already set.");
            }
            return false;
        }
        if (!is_null($this->modified_at)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: modified_at value already set.");
            }
            return false;
        }
        return true;
    }

    /**
     * @throws ValidationException
     */
    public function validateForDbUpdate(bool $optThrowExceptions = true) : bool {
        // ID is required
        if (empty($this->user_id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB update: ID value is not set.");
            }
            return false;
        }
        // username is required
        if (empty($this->username)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB update: username value not set.");
            }
            return false;
        }
        // password_hash is required
        if (empty($this->password_hash)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB update: password_hash value not set.");
            }
            return false;
        }
        return true;
    }

    /**
     * @throws ValidationException
     */
    public function validateForDbDelete(bool $optThrowExceptions = true) : bool {
        // ID is required
        if (empty($this->user_id)) {
            if ($optThrowExceptions) {
                throw new ValidationException("UserDTO is not valid for DB creation: ID value is not set.");
            }
            return false;
        }
        return true;
    }
    
    // </editor-fold>
    
    
    // <editor-fold defaultstate="collapsed" desc="GETTERS AND SETTERS">
    
    /**
     * @return int
     */
    public function getId() : int {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @throws ValidationException
     */
    public function setId(int $user_id) : void {
        if ($user_id <= 0) {
            throw new ValidationException("Invalid value for UserDTO [user_id]: must be a positive integer > 0.");
        }
        $this->user_id = $user_id;
    }
    
    /**
     * @return string
     */
    public function getUsername() : string {
        return $this->username;
    }

    /**
     * @param string $username
     * @throws ValidationException
     */
    public function setUsername(string $username) : void {
        if (mb_strlen($username) > self::USERNAME_MAX_LENGTH) {
            throw new ValidationException("Invalid value for UserDTO [username]: string length is > " .
                                          self::USERNAME_MAX_LENGTH . ".");
        }
        $this->username = $username;
    }    
    /**
     * @return string
     */

    public function getPassword() : string {
        return $this->password_hash;
    }

    /**
     * @param string $password_hash
     * @throws ValidationException
     */
    public function setPassword(string $password_hash) : void {
        if (mb_strlen($password_hash) > self::PASSWORD_MAX_LENGTH) {
            throw new ValidationException("Invalid value for UserDTO [password_hash]: string length is > " .
                self::PASSWORD_MAX_LENGTH . ".");
        }
        $this->password_hash = $password_hash;
    }
    /**
     * @return string
     */
    
    public function getEmail() : string {
        return $this->email;
    }
    
    /**
     * @param string $email
     */
    public function setEmail(string $email) : void {
        $this->email = $email;
    }
    
    /**
     * @return DateTime
     */
    public function getDateCreated() : DateTime {
        return $this->created_at;
    }
    
    /**
     * @param DateTime $created_at
     */
    public function setDateCreated(DateTime $created_at) : void {
        $this->created_at = $created_at;
    }
    
    /**
     * @return DateTime|null
     */
    public function getDateLastModified() : ?DateTime {
        return $this->modified_at;
    }
    
    /**
     * @param DateTime|null $modified_at
     */
    public function setDateLastModified(?DateTime $modified_at) : void {
        $this->modified_at = $modified_at;
    }
    
    /**
     * @return DateTime|null
     */

    /**
     * TODO: Function documentation
     *
     * @param bool $forceReload [default=false] If <code>true</code>, forces the reload of the group records from the database.
     * @return array
     * @throws RuntimeException
     */
    public function getGroups(bool $forceReload = false) : array {
        try {
            if (empty($this->groups) || $forceReload) {
                $this->loadGroups();
            }
        } catch (Exception $excep) {
            throw new RuntimeException("Failed to load group entity records for user user_id# [$this->user_id].", $excep->getCode(), $excep);
        }
        return $this->groups;
    }
    
    // </editor-fold>


    /**
     * @throws RuntimeException
     * @throws ValidationException
     */
    public function loadGroups() : void {
        $dao = new UserDAO();
        $this->groups = $dao->getGroupsByUser($this);
    }
    
    public function toArray() : array {
        $array = [
            "user_id" => $this->getId(),
            "username" => $this->getUsername(),
            "password_hash" => $this->getPassword(),
            "email" => $this->getEmail(),
            "created_at" => $this->getDateCreated()?->format(HTML_DATETIME_FORMAT),
            "modified_at" => $this->getDateLastModified()?->format(HTML_DATETIME_FORMAT),
            "groups" => []
        ];
        // Note: i'm not using getGroups() here in order not to trigger the loading of the groups.
        // Include them in the array only if loaded previously.
        // otherwise infinite loop user loads groups loads users loads groups loads users...
        foreach ($this->groups as $group) {
            $array["groups"][$group->getId()] = $group->toArray();
        }
        return $array;
    }
    
    
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }

    
}