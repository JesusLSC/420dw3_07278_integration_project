<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project PageNavigator.php
 * 
 * @user Marc-Eric Boury (MEbou)
 * @since 2024-03-26
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Controllers;

use GivenCode\Abstracts\IService;

/**
 * TODO: Class documentation
 *
 * @user Marc-Eric Boury
 * @since  2024-03-26
 */
class PageNavigator implements IService {
    
    
    public static function loginPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_FRAGMENTS_DIR . "page.login.php";
    }
    
    public static function groupsManagementPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_FRAGMENTS_DIR . "page.management.groups.php";
    }
    
    public static function usersManagementPage() : void {
        header("Content-Type: text/html;charset=UTF-8");
        include PRJ_FRAGMENTS_DIR . "page.management.users.php";
    }
}