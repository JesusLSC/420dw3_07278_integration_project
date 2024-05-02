<?php
declare(strict_types=1);

namespace DTOs;

use DateTime;
use Exception;
use DAOs\GroupDAO;
use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;


class GroupDTO {
    
    /**
     * The database table group_name for this entity type.
     * @const
     */
    public const TABLE_NAME = "usergroups";
    public const NAME_MAX_LENGTH = 255;
    public const DESCRIPTION_MAX_LENGTH = 1024;
    
    private int $group_id;
    private string $group_name;
    private ?string $group_description = null;
    private ?DateTime $dateCreated = null;
    private ?DateTime $dateLastModified = null;
    
    /**
     * TODO: Property documentation
     *
     * @var UserDTO[]
     */
    private array $users = [];
    
    public function __construct() {}
    
    /**
     * TODO: Function documentation
     *
     * @param string      $name
     * @param string|null $group_description
     * @return GroupDTO
     * @throws ValidationException
     */
    public static function fromValues(string $name,?string $group_description = null) : GroupDTO {
        $instance = new GroupDTO();
        $instance->setName($name);
        $instance->setDescription($group_description);
        return $instance;
    }
    
    /**
     * TODO: Function documentation
     *
     * @param array $dbArray
     * @return GroupDTO
     * @throws ValidationException
     */
    public static function fromDbArray(array $dbArray) : GroupDTO {
        self::validateDbArray($dbArray);
        $instance = new GroupDTO();
        $instance->setId((int) $dbArray["group_id"]);
        $instance->setName($dbArray["group_name"]);
        $instance->setDescription($dbArray["group_description"]);
        $instance->setDateCreated(DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["created_at"]));
        $dateLast_modified = null;
        if (!empty($dbArray["modified_at"])) {
            $dateLast_modified = DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["modified_at"]);
        }
        $instance->setDateLastModified($dateLast_modified);
        return $instance;
    }    
    
    public function getDatabaseTableName() : string {
        return self::TABLE_NAME;
    }
    
    // <editor-fold defaultstate="collapsed" desc="GETTERS AND SETTERS">
    
    /**
     * @return int
     */
    public function getId() : int {
        return $this->group_id;
    }

    /**
     * @param int $id
     * @throws ValidationException
     */
    public function setId(int $id) : void {
        if ($id < 1) {
            throw new ValidationException("[group_id] value must be a positive integer greater than 0.");
        }
        $this->group_id = $id;
    }
    
    /**
     * @return string
     */
    public function getName() : string {
        return $this->group_name;
    }

    /**
     * @param string $name
     * @throws ValidationException
     */
    public function setName(string $name) : void {
        if (mb_strlen($name) > self::NAME_MAX_LENGTH) {
            throw new ValidationException("[group_name] value must be a string no longer than " . self::NAME_MAX_LENGTH .
                                          " characters; found length: [" . mb_strlen($name) . "].");
        }
        $this->group_name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->group_description;
    }

    /**
     * @param string|null $group_description
     */
    public function setDescription(?string $group_description): void {
        $this->group_description = $group_description;
    }

    /**
     * @return DateTime
     */
    public function getDateCreated() : DateTime {
        return $this->dateCreated;
    }
    
    /**
     * @param DateTime $dateCreated
     */
    public function setDateCreated(DateTime $dateCreated) : void {
        $this->dateCreated = $dateCreated;
    }
    
    /**
     * @return DateTime|null
     */
    public function getDateLastModified() : ?DateTime {
        return $this->dateLastModified;
    }
    
    /**
     * @param DateTime|null $dateLastModified
     */
    public function setDateLastModified(?DateTime $dateLastModified) : void {
        $this->dateLastModified = $dateLastModified;
    }
    
    /**
     * TODO: function documentation
     *
     * @param bool $forceReload
     * @return array
     * @throws RuntimeException
     */
    public function getUsers(bool $forceReload = false) : array {
        try {
            if (empty($this->groups) || $forceReload) {
                $this->loadUsers();
            }
        } catch (Exception $excep) {
            throw new RuntimeException("Failed to load user entity records for group group_id# [$this->group_id].", $excep->getCode(), $excep);
        }
        return $this->users;
    }
    
    // </editor-fold>


    /**
     * @throws RuntimeException
     */
    public function loadUsers() : void {
        $dao = new GroupDAO();
        $this->users = $dao->getUsersByGroup($this);
    }
    
    public function toArray() : array {
        $array = [
            "group_id" => $this->getId(),
            "group_name" => $this->getName(),
            "group_description" => $this->getDescription(),
            "dateCreated" => $this->getDateCreated()?->format(HTML_DATETIME_FORMAT),
            "dateLastModified" => $this->getDateLastModified()?->format(HTML_DATETIME_FORMAT),
            "users" => []
        ];
        // Note: i'm not using getUsers() here in order not to trigger the loading of the users.
        // Include them in the array only if loaded previously.
        // otherwise infinite loop group loads users loads groups loads users loads groups...
        foreach ($this->users as $user) {
            $array["users"][$user->getId()] = $user->toArray();
        }
        return $array;
    }
    
    
    // <editor-fold defaultstate="collapsed" desc="VALIDATION METHODS">

    /**
     * @throws ValidationException
     */
    public function validateForDbCreation() : void {
        // ID must not be set
        if (!empty($this->group_id)) {
            throw new ValidationException("GroupDTO is not valid for DB creation: ID value already set.");
        }
        // group_name is required
        if (empty($this->group_name)) {
            throw new ValidationException("GroupDTO is not valid for DB creation: group_name value not set.");
        }
        if (!is_null($this->dateCreated)) {
            throw new ValidationException("GroupDTO is not valid for DB creation: dateCreated value already set.");
        }
        if (!is_null($this->dateLastModified)) {
            throw new ValidationException("GroupDTO is not valid for DB creation: dateLastModified value already set.");
        }
    }

    /**
     * @throws ValidationException
     */
    public function validateForDbUpdate() : void {
        // ID must be set
        if (empty($this->group_id)) {
            throw new ValidationException("GroupDTO is not valid for DB update: ID value not set.");
        }
        // group_name is required
        if (empty($this->group_name)) {
            throw new ValidationException("GroupDTO is not valid for DB update: group_name value not set.");
        }
    }

    /**
     * @throws ValidationException
     */
    public function validateForDbDelete() : void {
        // ID must be set
        if (empty($this->group_id)) {
            throw new ValidationException("GroupDTO is not valid for DB update: ID value not set.");
        }
    }
    
    
    /**
     * TODO: Function documentation
     *
     * @param array $dbArray
     * @return void
     * @throws ValidationException
     */
    private static function validateDbArray(array $dbArray) : void {
        if (empty($dbArray["group_id"])) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] does not contain an [group_id] key. Check your column names.",
                                          500);
        }
        if (empty($dbArray["group_name"])) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] does not contain an [group_name] key. Check your column names.",
                                          500);
        }
        if (empty($dbArray["created_at"])) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] does not contain an [created_at] key. Check your column names.",
                                          500);
        }
        if (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["created_at"]) === false) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] [created_at] entry value could not be parsed to a valid DateTime. Check your column types.",
                                          500);
        }
        if (!empty($dbArray["modified_at"])
            && (DateTime::createFromFormat(DB_DATETIME_FORMAT, $dbArray["modified_at"]) === false)
        ) {
            throw new ValidationException("Database array for [" . self::class .
                                          "] [modified_at] entry value could not be parsed to a valid DateTime. Check your column types.",
                                          500);
        }
    }
    
    
    // </editor-fold>
}