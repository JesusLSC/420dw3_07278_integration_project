<?php
declare(strict_types=1);

namespace Controllers;

use GivenCode\Exceptions\RuntimeException;
use GivenCode\Exceptions\ValidationException;
use Services\UserService;
use Services\LoginService;
use GivenCode\Abstracts\AbstractController;
use GivenCode\Exceptions\RequestException;

class UserController extends AbstractController {
    
    private UserService $userService;
    
    public function __construct() {
        parent::__construct();
        $this->userService = new UserService();
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     * @throws RequestException
     */
    public function get() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        if (empty($_REQUEST["userId"])) {
            throw new RequestException("Bad request: required parameter [userId] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["userId"])) {
            throw new RequestException("Bad request: parameter [userId] value [" . $_REQUEST["userId"] .
                                       "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["userId"];
        $instance = $this->userService->getUserById($int_id);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }

    /**
     * @throws RuntimeException
     * @throws RequestException
     */
    public function post() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        if (empty($_REQUEST["username"])) {
            throw new RequestException("Bad request: required parameter [username] not found in the request.", 400);
        }
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the
        // ExampleDTO class used when creating an ExampleDTO instance in the creation method of ExampleService.
        
        $instance = $this->userService->createUser($_REQUEST["username"], $_REQUEST["lastName"]);
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }

    /**
     * @throws RuntimeException
     * @throws ValidationException
     * @throws RequestException
     */
    public function put() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        $request_contents = file_get_contents("php://input");
        parse_str($request_contents, $_REQUEST);
        
        if (empty($_REQUEST["id"])) {
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) {
            throw new RequestException("Bad request: ivalid parameter [id] value: non-numeric value found [" .
                                       $_REQUEST["id"] . "].", 400);
        }
        if (empty($_REQUEST["username"])) {
            throw new RequestException("Bad request: required parameter [username] not found in the request.", 400);
        }
        
        // NOTE: no need for validation of the string lengths here, as that is done by the setter methods of the
        // ExampleDTO class used when creating an ExampleDTO instance in the creation method of ExampleService.
        
        $int_id = (int) $_REQUEST["id"];
        
        $instance = $this->userService->updateUser($int_id, $_REQUEST["username"], $_REQUEST["lastName"]);
        $instance->loadGroups();
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($instance->toArray());
    }

    /**
     * @throws RuntimeException
     * @throws RequestException
     */
    public function delete() : void {
        
        // Login required to use this API functionality
        if (!LoginService::isUserLoggedIn()) {
            // not logged-in: respond with 401 NOT AUTHORIZED
            throw new RequestException("NOT AUTHORIZED", 401, [], 401);
        }
        
        $request_contents = file_get_contents("php://input");
        parse_str($request_contents, $_REQUEST);
        
        if (empty($_REQUEST["id"])) {
            throw new RequestException("Bad request: required parameter [id] not found in the request.", 400);
        }
        if (!is_numeric($_REQUEST["id"])) {
            throw new RequestException("Bad request: parameter [id] value [" . $_REQUEST["id"] .
                                       "] is not numeric.", 400);
        }
        $int_id = (int) $_REQUEST["id"];
        $this->userService->deleteUserById($int_id);
        header("Content-Type: application/json;charset=UTF-8");
        http_response_code(204);
    }
}