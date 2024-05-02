<?php
declare(strict_types=1);


namespace Controllers;

use Exception;
use Services\LoginService;
use GivenCode\Abstracts\AbstractController;
use GivenCode\Exceptions\RequestException;


class LoginController extends AbstractController {
    
    private LoginService $loginService;
    
    public function __construct() {
        parent::__construct();
        $this->loginService = new LoginService();
    }

    /**
     * @throws RequestException
     */
    public function get() : void {
        // Voluntary exception throw: no GET operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }

    /**
     * @throws Exception
     */
    public function post() : void {
        /*
         * NOTE: I use the POST method to trigger the login
         */
        
        try {
            if (empty($_REQUEST["userId"])) {
                throw new RequestException("Missing required parameter [userId] in request.", 400, [], 400);
            }
            if (!is_numeric($_REQUEST["userId"])) {
                throw new RequestException("Invalid parameter [userId] in request: non-numeric value [" .
                                           $_REQUEST["userId"] . "] received.",
                                           400, [], 400);
            }

            $int_id = (int) $_REQUEST["userId"];
            $this->loginService->doLogin($int_id);
            
            // if the user came to the login page by being redirected from another page that required to be logged in
            // redirect to that originally requested page after login.
            $response = [
                "navigateTo" => WEB_ROOT_DIR
            ];
            if (!empty($_REQUEST["from"])) {
                $response["navigateTo"] = $_REQUEST["from"];
            }
            header("Content-Type: application/json;charset=UTF-8");
            echo json_encode($response);
            exit();
            
        }
        catch (Exception $excep) {
            $code = $excep->getCode();
            if (!is_int($code)) {
                $code = 500;
            }
            throw new Exception("Failure to log user in.", $code, $excep);
        }
    }

    /**
     * @throws RequestException
     */
    public function put() : void {
        // Voluntary exception throw: no PUT operation supported for login system
        throw new RequestException("NOT IMPLEMENTED.", 501);
    }
    
    public function delete() : void {
        /*
         * NOTE: I use the DELETE method to trigger the logout
         */
        
        $this->loginService->doLogout();
        $response = [
            "navigateTo" => WEB_ROOT_DIR . "pages/login"
        ];
        if (!empty($_REQUEST["from"])) {
            $response["navigateTo"] = $_REQUEST["from"];
        }
        header("Content-Type: application/json;charset=UTF-8");
        echo json_encode($response);
        exit();
    }
}